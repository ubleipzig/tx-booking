<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Factory;

class DayController extends ActionController {

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

	/**
	 * @param integer $day
	 */
	public function showAction($day) {
	}
}
