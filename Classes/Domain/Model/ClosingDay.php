<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use FluidTYPO3\Flux\Form\Field\DateTime;
use LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;

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

	public function __construct($name, $description = '', $date) {
		$this->setName($name);
		$this->setDescription($description);
		$this->setDate($date);
	}

	public function getDay() {
		return date('j', $this->date);
	}

	public function getMonth() {
		return date('n', $this->date);
	}

	public function getYear() {
		return date('Y', $this->date);
	}
}
