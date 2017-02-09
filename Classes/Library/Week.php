<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Library;

class Week extends DateIterator implements \Iterator {

	public function __construct($timestamp = null) {
		parent::__construct($timestamp);

		$this->origin->modify('Monday this week');
		$this->current = clone $this->origin;
	}

	public function current() {
		return new Day($this->current->getTimestamp());
	}

	public function key() {
		return $this->current->format('N') - 1;
	}

	public function next() {
		$this->current->modify('next day');
	}

	public function valid() {
		return ($this->current->format('W') === $this->origin->format('W'));
	}
}