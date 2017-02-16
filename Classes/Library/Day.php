<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Library;

class Day extends DateIterator implements \Iterator {

	/**
	 * original time
	 *
	 * @var \DateTime
	 */
	protected $origin;

	/**
	 * current time of iteration cycle
	 *
	 * @var \DateTime
	 */
	protected $current;

	/**
	 * starting hour of day
	 *
	 * @var int
	 */
	protected $start;

	/**
	 * ending hour of day
	 *
	 * @var int
	 */
	protected $end;

	public function __construct($timestamp = null, $start = 0, $end = 23) {
		parent::__construct($timestamp);

		if ($start > $end) throw new \Exception('start must not be greater than end');
		$this->start = $start;
		$this->end = $end;

		$this->origin = $this->origin->modify('midnight');
		$this->current = $this->origin;
	}

	public function current() {
		return new Hour($this->current->getTimestamp());
	}

	public function key() {
		return (int)$this->current->format('H');
	}

	public function next() {
		$this->current = $this->current->modify('next hour');
	}

	public function rewind() {
		$this->current = $this->origin;
		if ($this->start > 0) $this->current = $this->current->add(new \DateInterval("PT{$this->start}H"));
	}

	public function valid() {
		return (($this->current->format('d') === $this->origin->format('d'))
			&& ((int)$this->current->format('H') <= $this->end));
	}

	public function getTitle() {
		return $this->origin->format('d.m.Y');
	}

	public function getStart() {
		return $this->origin->add(new \DateInterval("PT{$this->start}H"));
	}

	public function getEnd() {
		return $this->origin->add(new \DateInterval("PT{$this->end}H"));
	}
}