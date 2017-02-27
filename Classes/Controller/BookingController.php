<?php
namespace LeipzigUniversityLibrary\UblBooking\Controller;

use \LeipzigUniversityLibrary\UblBooking\Library\Week;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;
use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking;

class BookingController extends AbstractController {

	/**
	 * $bookingRepository
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Room
	 * @inject
	 */
	protected $roomRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @param integer $timestamp
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 */
	public function showDayAction($timestamp, $room) {
		$today = new Day();
		$day = new Day($timestamp);
		$room->fetchDayOccupation($day);
		$room->setSettingsHelper($this->settingsHelper);
		$this->view->assign('Room', $room);

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showNextDay($day)) {
			$this->view->assign('nextDay', $room->getDay()->modify('next day')->getTimestamp());
		}

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showPreviousDay($day)) {
			$this->view->assign('previousDay', $room->getDay()->modify('previous day')->getTimestamp());
		}
	}

	/**
	 * @param integer $timestamp
	 */
	public function showWeekAction($timestamp = null) {
		$week = new Week($timestamp);

		$rooms = $this->roomRepository->findAllWithOccupationForWeek($week, $this->settingsHelper);
		$today = new Day();
		$now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin'));


		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showNextWeek($week)) {
			$this->view->assign('nextWeek', $week->modify('Monday next week')->getTimestamp());
		}

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showPreviousWeek($week)) {
			$this->view->assign('previousWeek', $week->modify('Monday last week')->getTimestamp());
		}
		$this->view->assign('Today', $today);
		$this->view->assign('Now', $now);
		$this->view->assign('Rooms', $rooms);
	}
	/**
	 * adds a booking
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param integer $timestamp
	 * @param string $comment
	 */
	public function addAction($room, $timestamp, $comment) {
		$hour = new Hour($timestamp);
		$day = new Day($timestamp);
		$today = new Day();
		$room->fetchDayOccupation($day);

		if ($day->getDateTime() < $today->getDateTime()) {
			$this->addFlashMessage('bookingInPast', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (!$this->settingsHelper->isAdmin() && $this->settingsHelper->exceededBookingLimit($timestamp)) {
			$this->addFlashMessage('bookingInFuture', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (!$room->isHourBookable($hour)) {
			$this->addFlashMessage('alreadyBookedByForeignUser', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (count($this->bookingRepository->findByUserAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $hour->getDateTime())) > 0) {
			$this->addFlashMessage('alreadyBookedInOtherRoom', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if ($this->settingsHelper->getMaxBookings() && count($this->bookingRepository->findByUserAndBetween($GLOBALS['TSFE']->fe_user->user['uid'], $day->getStart(), $day->getEnd())) === $this->settingsHelper->getMaxBookings()) {
			$this->addFlashMessage('maxBookingsReached', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else {
			$this->bookingRepository->add(new Booking($timestamp, $room, $comment));
			$this->addFlashMessage('successfullyBooked', \TYPO3\CMS\Core\Messaging\FlashMessage::OK);
		}

		$this->redirect('showDay', 'Booking', null, ['timestamp' => $timestamp, 'room' => $room]);
	}

	/**
	 * removes a booking
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param integer $timestamp
	 */
	public function removeAction($room, $timestamp) {
		$hour = new Hour($timestamp);
		$day = new Day($timestamp);
		$room->fetchDayOccupation($day);
		$now = new Hour();

		// booking in the past, do not allow to remove
		if ($now->getDateTime() > $hour->getDateTime()) {
			$this->addFlashMessage('cannotRemovePastBooking', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if ($booking = $this->bookingRepository->findByUserAndRoomAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $room, $hour->getDateTime())->getFirst()) {
			$this->addFlashMessage('bookingSuccessfullyRemoved');
			$this->bookingRepository->remove($booking);
		} else {
			$this->addFlashMessage('noBookingToRemove', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}

		$this->redirect('showDay', 'Booking', null, ['timestamp' => $timestamp, 'room' => $room]);
	}
}
