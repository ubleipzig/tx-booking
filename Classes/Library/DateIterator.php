<?php

namespace LeipzigUniversityLibrary\UblBooking\Library;

class DateIterator extends DateHelper {

	/**
	 * @var \DateTime
	 */
	protected $current;

	public function rewind() {
		$this->current = $this->origin;
	}

}