<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Factory;

class WeekController extends ActionController {

	/**
	 * $closingDayRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\ClosingDay
	 * @inject
	 */
	protected $closingDayRepository;

	/**
	 * $bookingRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * $roomRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\Room
	 * @inject
	 */
	protected $roomRepository;

	public function showAction() {
//		$week = Factory::getWeekByTime(DateTime $datetime);
//		$rooms = Factory::getRoomsByWeek($week);

		$closingDays = $this->closingDayRepository->findAll();
//		$rooms = $this->roomRepository->findAll();
//		$bookings = $this->bookingRepository->findAll();

		$this->view->assign('closingDays', $closingDays);
//		$this->view->assign('rooms', $rooms);
//		$this->view->assign('bookings', $bookings);
	}
}
