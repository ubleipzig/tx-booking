<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;

class ClosingDay extends AbstractEntity {

	/**
	 * day of week
	 *
	 * @var integer
	 **/
	protected $weekDay;

	/**
	 * list of hours
	 *
	 * @var array
	 */
	protected $dutyHours;

	public function __construct($datetime, array $dutyHours = []) {
		$this->weekDay = (int)$this->current->format('H');
		$this->dutyHours = $dutyHours;
	}
}
