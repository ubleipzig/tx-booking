<?php

namespace LeipzigUniversityLibrary\UblBooking\Library;

class Week extends DateIterator implements \Iterator, \Countable {

	/**
	 * when the average day starts
	 *
	 * @var integer
	 */
	protected $dayStart;

	/**
	 * when the average day ends
	 *
	 * @var integer
	 */
	protected $dayEnd;

	public function __construct($timestamp = null, $dayStart = 0, $dayEnd = 23) {
		parent::__construct($timestamp);

		$this->origin = $this->origin->modify('Monday this week');
		$this->current = $this->origin;
		$this->dayStart = $dayStart;
		$this->dayEnd = $dayEnd;
	}

	public function current() {
		return new Day($this->current->getTimestamp(), $this->dayStart, $this->dayEnd);
	}

	public function key() {
		return $this->current->format('N');
	}

	public function next() {
		$this->current = $this->current->modify('next day');
	}

	public function valid() {
		return ($this->current->format('W') === $this->origin->format('W'));
	}

	public function getStart() {
		return $this->origin->modify('Monday this week');
	}

	public function getEnd() {
		return $this->origin->modify('Sunday this week');
	}

	public function count() {
		return 7;
	}
}