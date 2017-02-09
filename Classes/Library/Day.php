<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Library;

class Day extends DateIterator implements \Iterator {

	/**
	 * @var \DateTime
	 */
	protected $origin;

	protected $current;

	public function __construct($timestamp = null) {
		parent::__construct($timestamp);

		$this->origin->modify('midnight');
		$this->current = clone $this->origin;
	}

	public function current() {
		return clone $this->current;
	}

	public function key() {
		return $this->current->format('h');
	}

	public function next() {
		$this->current->modify('next hour');
	}

	public function valid() {
		return ($this->current->format('d') === $this->origin->format('d'));
	}
}