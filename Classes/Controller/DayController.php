<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking;

class DayController extends AbstractController {

	/**
	 * $roomRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\Room
	 * @inject
	 */
	protected $roomRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;

	/**
	 * @param integer $day
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 */
	public function showAction($day, $room) {
		$today = new Day();

		$room->fetchDayOccupation($day);
		$room->setSettings($this->settings);
		$this->view->assign('Room', $room);
		if (true) $this->view->assign('nextDay', $room->getDay()->modify('next day')->getTimestamp());
		if (true) $this->view->assign('previousDay', $room->getDay()->modify('previous day')->getTimestamp());
	}

	/**
	 * adds a booking
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param integer $timestamp
	 * @param string $comment
	 */
	public function addBookingAction($room, $timestamp, $comment) {
		$hour = new Hour($timestamp);
		$day = new Day($timestamp);
		$room->fetchDayOccupation($timestamp);

		if (!$room->isHourBookable($hour)) {
			$this->addFlashMessage('already booked by another user', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (count($this->bookingRepository->findByUserAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $hour->getDateTime())) > 0) {
			$this->addFlashMessage('already booked in another room', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (count($this->bookingRepository->findByUserAndBetween($GLOBALS['TSFE']->fe_user->user['uid'], $day->getStart(), $day->getEnd())) >= 2) {
			$this->addFlashMessage('max slots booked', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else {
			$this->bookingRepository->add(new Booking($timestamp, $room, $comment));
			$this->addFlashMessage('slot successfully booked', \TYPO3\CMS\Core\Messaging\FlashMessage::OK);
		}

		$this->redirect('show', 'Day', null, ['day' => $timestamp, 'room' => $room]);
	}

	/**
	 * removes a booking
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param integer $timestamp
	 */
	public function removeBookingAction($room, $timestamp) {
		$hour = new Hour($timestamp);
		$room->fetchDayOccupation($timestamp);
		$now = new Hour();

		// booking in the past, do not allow to remove
		if ($now->getDateTime() > $hour->getDateTime()) {
			$this->addFlashMessage('bookings in the past cannot be removed', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if ($booking = $this->bookingRepository->findByUserAndRoomAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $room, $hour->getDateTime())->getFirst()) {
			$this->addFlashMessage('booking successfully removed');
			$this->bookingRepository->remove($booking);
		} else {
			$this->addFlashMessage('no booking to remove', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}

		$this->redirect('show', 'Day', null, ['day' => $timestamp, 'room' => $room]);
	}
}
