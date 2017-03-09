<?php
/**
 * Class Room
 *
 * Copyright (C) Leipzig University Library 2017 <info@ub.uni-leipzig.de>
 *
 * @author  Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

namespace LeipzigUniversityLibrary\UblBooking\Domain\Model;

use \LeipzigUniversityLibrary\UblBooking\Domain\Repository\OpeningHours;
use \LeipzigUniversityLibrary\UblBooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\UblBooking\Library\Week;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

/**
 * Class Room
 *
 * @package LeipzigUniversityLibrary\UblBooking\Domain\Model
 */
class Room extends AbstractEntity {

	/**
	 * The week representation of the room
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Library\Week
	 */
	protected $week;

	/**
	 * The day representation of the room
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Library\Day
	 */
	protected $day;

	/**
	 * The opening hours resolved as array
	 *
	 * @var array
	 */
	protected $openingHours = [];

	/**
	 * in the past
	 */
	const PAST = 1;

	/**
	 * not bookable
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
	 * the name of the room
	 *
	 * @var string
	 **/
	protected $name;

	/**
	 * The description of the room
	 *
	 * @var string
	 **/
	protected $description;

	/**
	 * Where the opening times and closing days for the room are stored
	 * if empty take the plugins configured storage
	 *
	 * @var string
	 */
	protected $openingTimesStorage;

	/**
	 * Where the bookings are stored. if empty take the plugins configured storage
	 *
	 * @var int
	 */
	protected $bookingStorage;

	/**
	 * The repository of the closing days
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\ClosingDay
	 * @inject
	 */
	protected $closingDayRepository;

	/**
	 * The repository of the Bookings
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * The repository of the opening ours
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\OpeningHours
	 * @inject
	 */
	protected $openingHoursRepository;

	/**
	 * The room's bookings
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking>
	 * @lazy
	 * @cascade remove
	 */
	protected $bookings;

	/**
	 * The room's closing days
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\LeipzigUniversityLibrary\UblBooking\Domain\Model\ClosingDay>
	 * @lazy
	 */
	protected $closingDays;

	/**
	 * We are initializing the storage objects here since the constructor is not invoked for model when created by
	 * repositories
	 */
	public function initializeObject() {
		$this->bookings = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->closingDays = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		return parent::initializeObject();
	}

	/**
	 * Adds a booking to the storage container
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking $booking
	 * @return void
	 */
	public function addBooking(Booking $booking) {
		$this->bookings->attach($booking);
	}

	/**
	 * Removes a booking from the storage container
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking $booking
	 * @return void
	 */
	public function removeBooking(Booking $booking) {
		$this->bookings->detach($booking);
	}

	/**
	 * Adds a closing day to the storage container
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\ClosingDay $closingDay
	 * @return void
	 */
	public function addClosingDay(ClosingDay $closingDay) {
		$this->closingDays->attach($closingDay);
	}

	/**
	 * Removes a closing day from the storage container
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\ClosingDay $closingDay
	 * @return void
	 */
	public function removeClosingDay(ClosingDay $closingDay) {
		$this->closingDays->detach($closingDay);
	}

	/**
	 * Fetches the Bookings of the room for a specified week
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Week $week the week to fetch bookings for
	 */
	public function fetchWeekOccupation(Week $week) {
		$this->setOpeningHours($this->openingHoursRepository->findAllByRoom($this));

		list($dayStart, $dayEnd) = $this->getMinMaxOpeningHours();
		$this->week = $week;
		$this->week->setDayStart($dayStart);
		$this->week->setDayEnd($dayEnd);

		foreach ($this->closingDayRepository->findByRoomAndBetween($this, $this->week->getStart(), $this->week->getEnd()) as $closingDay) {
			$this->addClosingDay($closingDay);
		}

		foreach ($this->bookingRepository->findByRoomAndBetween($this, $this->week->getStart(), $this->week->getEnd()) as $booking) {
			$this->addBooking($booking);
		}
	}

	/**
	 * Fetches all bookings of the room for a specified day
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Day $day the day to fetch bookings for
	 */
	public function fetchDayOccupation(Day $day) {
		$this->day = $day;

		$this->setOpeningHours($this->openingHoursRepository->findByRoomAndDay($this, $this->day->getDateTime()));

		list($dayStart, $dayEnd) = $this->getMinMaxOpeningHours($this->day);

		$this->day->setStart($dayStart);
		$this->day->setEnd($dayEnd);

		if ($closingDay = $this->closingDayRepository->findByRoomAndDay($this, $this->day->getDateTime())) $this->addClosingDay($closingDay);

		foreach ($this->bookingRepository->findByRoomAndBetween($this, $this->day->getStart(), $this->day->getEnd()) as $booking) {
			$this->addBooking($booking);
		}
	}

	/**
	 * return the kind of occupation of the room for a specified hour
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour the hour to get the occupation for
	 * @return int the occupation as constant
	 */
	public function getHourOccupation(Hour $hour) {
		if (!$this->isDutyHour($hour)) return self::OFFDUTY;

		$day = new Day($hour->getTimestamp());

		if ($this->getDayOccupation($day) === self::OFFDUTY) {
			return self::OFFDUTY;
		}

		if ($booking = $this->getBooking($hour->getDateTime())) {
			if ($booking->getFeUser() === $GLOBALS['TSFE']->fe_user->user['uid']) return self::OWNBOOKED;
			if ($this->settingsHelper->isAdmin($booking->getFeUser())) return self::OFFDUTY;
			return self::FOREIGNBOOKED;
		};

		return self::AVAILABLE;
	}

	/**
	 * Returns overall occupation of a room for a day
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Day $day the day to get the occupaton for
	 * @return int the occupation as constant
	 */
	public function getDayOccupation(Day $day) {
		foreach ($this->closingDays as $closingDay) {
			if ($closingDay->getDay()->getTimestamp() === $day->getTimestamp()) return self::OFFDUTY;
		}

		return self::AVAILABLE;
	}

	/**
	 * Whether the day is bookable.
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Day $day the day to test bookability for
	 * @return bool true if bookable
	 */
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
	 * Whether the hour is bookable
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour the hour to test bookability for
	 * @return bool true if bookable
	 */
	public function isHourBookable(Hour $hour) {
		if (!$this->isDayBookable(new Day($hour->getTimestamp()))) return false;
		$now = new \DateTimeImmutable('now', new \DateTimeZone(date_default_timezone_get()));
		if ($hour->getDateTime() < $now) {
			return false;
		}
		$occupation = $this->getHourOccupation($hour);

		if (in_array($occupation, [self::OFFDUTY, self::FOREIGNBOOKED])) return false;

		if (!$GLOBALS['TSFE']->fe_user->user) return false;

		return true;
	}

	/**
	 * Sets the opening hours from query response
	 *
	 * @param \ArrayIterator $queryResponse the opening hours of the room
	 */
	public function setOpeningHours($queryResponse) {
		foreach ($queryResponse as $openingHours) {
			$this->openingHours[$openingHours->getWeekDay()] = empty($openingHours->getHours()) ? [] : explode(',', $openingHours->getHours());
		}
	}

	/**
	 * calculates the minimum and maximum opening hour
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Day $day [optional] if provided only get the values for the provided day.
	 *                                                              if not provided aggregated for the entire week
	 * @return array start and end
	 */
	public function getMinMaxOpeningHours(Day $day = null) {
		// not all days defined? at least one day has 24h opening times
		if (!$day && count($this->openingHours) < 7) return [0, 23];

		foreach ($this->openingHours as $dayOfWeek => $openingHours) {
			if ($day && ((int)$day->format('N') !== $dayOfWeek)) continue;
			if (count($openingHours) === 0) continue;
			$min = min($openingHours);
			$max = max($openingHours);
			if (!isset($start) || $min < $start) $start = $min;
			if (!isset($end) || $max > $end) $end = $max;
		}

		if (!isset($start) && !isset($end)) {
			$start = 0;
			$end = 23;
		}

		return [$start, $end];
	}

	/**
	 * Whether the provided hour of the room is a duty hour
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour the hour to test dutyness for
	 * @return bool true if duty hour
	 */
	public function isDutyHour(Hour $hour) {
		$day = $hour->format('N');
		return isset($this->openingHours[$day]) ? in_array($hour->format('H'), $this->openingHours[$day]) : true;
	}

	/**
	 * Returns the booking for a timestamp
	 *
	 * @param \DateTimeInterface $timestamp the time to get the booking for
	 * @return \LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking the booking if found
	 */
	public function getBooking(\DateTimeInterface $timestamp) {
		foreach ($this->bookings as $booking) {
			if ($booking->getDateTime() == $timestamp) return $booking;
		}
	}

	/**
	 * Returns the opening times storage pid of the room (if any specified)
	 *
	 * @return array of pids
	 */
	public function getOpeningTimesStorage() {
		return array_reduce(explode(',', $this->openingTimesStorage), function ($carry, $item) {
			if (!empty($item)) $carry[] = (int)$item;
			return $carry;
		}, []);
	}
}