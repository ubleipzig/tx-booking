<?php

namespace LeipzigUniversityLibrary\Ublbooking\Library;

class Hour extends DateHelper {

	public function __construct($timestamp = null) {
		parent::__construct($timestamp);

		$this->origin = $this->origin->modify('this hour');
	}
}