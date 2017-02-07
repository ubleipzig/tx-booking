<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;

class Hour extends AbstractEntity {

	/**
	 * not bookable at all
	 */
	const OFFDUTY = 1;

	/**
	 * available for booking
	 */
	const AVAILABLE = 2;

	/**
	 * already booked by someone else
	 */
	const FOREIGNBOOKED = 4;

	/**
	 * already booked by himself
	 */
	const OWNBOOKED = 8;

	/**
	 * which hour is it
	 *
	 * @var integer
	 */
	protected $dateTime;

	/**
	 * the status of the hour
	 *
	 * @var integer
	 */
	protected $status;

	/**
	 * hour constructor. 0-24
	 *
	 * @param integer hour
	 */
	public function __construct($hour) {
		$this->setDateTime($hour);
	}
}