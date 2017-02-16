<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Week;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;

class Room extends AbstractEntity {

	/**
	 * the week representation of the room
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Library\Week
	 */
	protected $week;

	/**
	 * the day representation of the room
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day
	 */
	protected $day;

	/**
	 * duty hours resolved as array
	 *
	 * @var array
	 */
	protected $dutyHours;

	/**
	 * in the past
	 */
	const PAST = 1;

	/**
	 * not bookable at all
	 */
	const OFFDUTY = 2;

	/**
	 * available for booking
	 */
	const AVAILABLE = 4;

	/**
	 * already booked by someone else
	 */
	const FOREIGNBOOKED = 8;

	/**
	 * already booked by himself
	 */
	const OWNBOOKED = 16;

	/**
	 * @var string
	 **/
	protected $name = '';

	/**
	 * @var string
	 **/
	protected $description = '';

	/**
	 * @var string
	 */
	protected $hours;

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

	public function fetchWeekOccupation($timestamp) {
		$this->week = new Week($timestamp, reset($this->getDutyHours()), end($this->getDutyHours()));

		foreach ($this->closingDayRepository->findBetween($this->week->getStart(), $this->week->getEnd()) as $closingDay) {
			$this->addClosingDay($closingDay);
		}

		foreach ($this->bookingRepository->findByRoomAndBetween($this, $this->week->getStart(), $this->week->getEnd()) as $booking) {
			$this->addBooking($booking);
		}
	}

	public function fetchDayOccupation($timestamp) {
		$this->day = new Day($timestamp, reset($this->getDutyHours()), end($this->getDutyHours()));

		if ($closingDay = $this->closingDayRepository->findByDay($this->day->getDateTime())) $this->addClosingDay($closingDay);

		foreach ($this->bookingRepository->findByRoomAndBetween($this, $this->day->getStart(), $this->day->getEnd()) as $booking) {
			$this->addBooking($booking);
		}
	}

	public function getHourOccupation(Hour $hour) {
		if (!in_array((int)$hour->format('H'), $this->getDutyHours())) {
			return self::OFFDUTY;
		}

		$day = new Day($hour->getTimestamp());

		if ($this->getDayOccupation($day) === self::OFFDUTY) {
			return self::OFFDUTY;
		}

		if ($booking = $this->findBooking($hour)) {
			switch ($booking->getUser()) {
				case $GLOBALS['TSFE']->fe_user->user['uid']:
					return self::OWNBOOKED;
				case '':
					return self::OFFDUTY;
				default:
					return self::FOREIGNBOOKED;
			}
		};

		return self::AVAILABLE;
	}

	public function findBooking(Hour $hour) {
		foreach ($this->bookings as $booking) {
			if ($booking->getDateTime() == $hour->getDateTime()) {
				return $booking;
			}
		}
	}

	public function getDayOccupation(Day $day) {
		foreach ($this->closingDays as $closingDay) {
			if ($closingDay->getDay()->getTimestamp() === $day->getTimestamp()) return self::OFFDUTY;
		}

		return self::AVAILABLE;
	}

	public function isDayBookable(Day $day) {
		$now = new Day();
		if ($day->getDateTime() < $now->getDateTime()) {
			return false;
		}

		if ($this->getDayOccupation($day) === self::OFFDUTY) return false;

		if (!$GLOBALS['TSFE']->fe_user->user) return false;

		return true;
	}

	/**
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour $hour
	 * @return bool
	 */
	public function isHourBookable(Hour $hour) {
		if (!$this->isDayBookable(new Day($hour->getTimestamp()))) return false;
		$now = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin'));
		if ($hour->getDateTime() < $now) {
			return false;
		}
		$occupation = $this->getHourOccupation($hour);

		if (in_array($occupation, [self::OFFDUTY, self::FOREIGNBOOKED])) return false;

		if (!$GLOBALS['TSFE']->fe_user->user) return false;

		return true;
	}

	public function setDutyHours($value = null) {
		if (!$value) {
			$this->dutyHours = array_unique(explode(',', $this->hours));
			sort($this->dutyHours);
		} else {
			$this->dutyHours = $value;
			$this->hours = implode(',', $this->dutyHours);
		}
	}

	public function getDutyHours() {
		if (!$this->dutyHours) $this->setDutyHours();
		return $this->dutyHours;
	}

	public function getBooking($timestamp) {
		foreach ($this->bookings as $booking) {
			if ($booking->getDateTime() == $timestamp) return $booking;
		}
	}
}