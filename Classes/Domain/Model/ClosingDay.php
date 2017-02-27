<?php

namespace LeipzigUniversityLibrary\UblBooking\Domain\Model;

use \LeipzigUniversityLibrary\UblBooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;

class ClosingDay extends AbstractEntity {
	/**
	 * @var string
	 **/
	protected $name = '';

	/**
	 * @var string
	 **/
	protected $description = '';

	/**
	 * @var integer
	 **/
	protected $date;

	/**
	 * @var \LeipzigUniversityLibrary\UblBooking\Library\Day
	 */
	protected $day;

	public function __construct($name, $description = '', $date) {
		$this->setName($name);
		$this->setDescription($description);
		$this->setDate($date);
	}

	public function getDay() {
		if (!$this->day) $this->setDay(new Day($this->date));

		return $this->day;
	}
}
