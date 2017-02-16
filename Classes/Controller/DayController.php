<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking;

class DayController extends ActionController {

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
		$this->view->assign('Room', $room);
		$this->view->assign('today', $today);
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
			throw new \Exception('already booked by another user');
			// flashMessage();
		}

		if (count($this->bookingRepository->findByUserAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $hour->getDateTime())) > 0) {
			throw new \Exception('already booked in another room');
			// flashMessage();
		}

		//
		if (count($this->bookingRepository->findByUserAndBetween($GLOBALS['TSFE']->fe_user->user['uid'], $day->getStart(), $day->getEnd())) >= 2) {
			throw new \Exception('max slots booked');
			// flashMessage();
		}

		$this->bookingRepository->add(new Booking($timestamp, $room, $comment));

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
			throw new \Exception('already booked by another user');
			// flashMessage();
		}

		$booking = $this->bookingRepository->findByUserAndRoomAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $room, $hour->getDateTime())->getFirst();

		if (!$booking) {
			throw new \Exception('no booking to remoe');
			// flashMessage();
		}

		$this->bookingRepository->remove($booking);

		$this->redirect('show', 'Day', null, ['day' => $timestamp, 'room' => $room]);
	}
}
