<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Library;

class DateIterator {

	/**
	 * @var \DateTime
	 */
	protected $origin;

	/**
	 * @var \DateTime
	 */
	protected $current;

	public function __construct($timestamp = null) {
		$this->origin = new \DateTime('now', new \DateTimeZone('Europe/Berlin'));

		if ($timestamp) $this->origin->setTimestamp($timestamp);
	}

	public function rewind() {
		$this->current = clone $this->origin;
	}

	public function __call($method, $args) {
		return call_user_func_array([clone $this->origin, $method], $args);
	}
}