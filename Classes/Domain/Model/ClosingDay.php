<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;

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
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day
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
