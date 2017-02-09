<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;

class Room extends AbstractEntity {
	/**
	 * @var string
	 **/
	protected $name = '';

	/**
	 * @var string
	 **/
	protected $description = '';

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
	 * a rooms bookings
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking>
	 * @lazy
	 * @cascade remove
	 */
	protected $bookings;

	/**
	 * a rooms closing days
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay>
	 * @lazy
	 */
	protected $closingDays;

	public function __construct() {
		echo 'foobar';
	}

	/**
	 * we are initializing the storage objects here since the constructor is not invoked for model when created by
	 * repositorys, god knows why
	 */
	public function initializeObject() {
		$this->bookings = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->closingDays = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		return parent::initializeObject();
	}

	/**
	 * Adds a booking
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking $booking
	 * @return void
	 */
	public function addBooking(\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking $booking) {
		$this->bookings->attach($booking);
	}

	/**
	 * Removes a booking
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking $booking
	 * @return void
	 */
	public function removeBooking(\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking $booking) {
		$this->bookings->detach($booking);
	}

	/**
	 * Adds a closing day
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay $closingDay
	 * @return void
	 */
	public function addClosingDay(\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay $closingDay) {
		$this->closingDays->attach($closingDay);
	}

	/**
	 * Removes a closing day
	 *
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay $closingDay
	 * @return void
	 */
	public function removeClosingDay(\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay $closingDay) {
		$this->closingDays->detach($closingDay);
	}

	public function getOccupancyBetween($startTime, $endTime) {
		foreach($this->closingDayRepository->findInBetween($startTime, $endTime) as $closingDay) {
			$this->addClosingDay($closingDay);
		}

		foreach($this->bookingRepository->findByRoomAndInBetween($startTime, $endTime, $this) as $booking) {
			$this->addBooking($booking);
		}
	}
}