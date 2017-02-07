<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Day;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Hour;

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
	 * a rooms bookings
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Booking>
	 */
	protected $bookings = null;

	/**
	 * a rooms closing days
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\ClosingDay>
	 */
	protected $closingDays = null;

	public function __construct($name = '') {
		return parent::initStorageObjects();
	}

	/**
	 * return overall day status
	 *
	 * @param \DateTime $dateTime
	 * @return int
	 */
	public function getDayStatus(\DateTime $dateTime) {
		return Day::AVAILABLE;
	}

	/**
	 * return hour status
	 *
	 * @param \DateTime $dateTime
	 */
	public function getHourStatus(\DateTime $dateTime) {
		return Hour::AVAILABLE;

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
}