<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;

class DutyHours extends AbstractEntity {

	/**
	 * day of week
	 *
	 * @var integer
	 **/
	protected $weekDay;

	/**
	 * list of hours
	 *
	 * @var string
	 */
	protected $hours;

	public function __construct($datetime, array $dutyHours = []) {
		$this->weekDay = (int)$this->current->format('H');
		$this->hours = $dutyHours;
	}
}
