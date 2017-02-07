<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;

class Day extends AbstractEntity {

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
	 * which day is it
	 *
	 * @var \DateTime
	 */
	protected $dateTime;

	/**
	 * the status of the day
	 *
	 * @var integer
	 */
	protected $status;

	/**
	 * Day constructor. needs a daytime to initialize
	 *
	 * @param \DateTime $dateTime
	 */
	public function __construct(\DateTime $dateTime) {
		$this->setDateTime($dateTime);
	}
}