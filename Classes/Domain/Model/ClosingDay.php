<?php

namespace LeipzigUniversityLibrary\Ublbooking\Domain\Model;

use \LeipzigUniversityLibrary\Ublbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\Ublbooking\Library\Day;

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
	 * @var \LeipzigUniversityLibrary\Ublbooking\Library\Day
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
