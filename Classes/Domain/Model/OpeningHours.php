<?php

namespace LeipzigUniversityLibrary\Ublbooking\Domain\Model;

use \LeipzigUniversityLibrary\Ublbooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\Ublbooking\Library\Day;

class OpeningHours extends AbstractEntity {

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

	public function __construct($datetime, array $openingHours = []) {
		$this->weekDay = (int)$this->current->format('H');
		$this->hours = $openingHours;
	}
}
