<?php

namespace LeipzigUniversityLibrary\UblBooking\Domain\Model;

use \LeipzigUniversityLibrary\UblBooking\Library\AbstractEntity;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;

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
