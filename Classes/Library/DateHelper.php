<?php

namespace LeipzigUniversityLibrary\Ublbooking\Library;

class DateHelper {

	/**
	 * @var \DateTimeImmutable
	 */
	protected $origin;

	public function __construct($timestamp = null) {
		$this->origin = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin'));

		if ($timestamp) $this->origin = $this->origin->setTimestamp($timestamp);
	}

	public function __call($method, $args) {
		return call_user_func_array([$this->origin, $method], $args);
	}

	public function getDateTime() {
		return $this->origin;
	}
}