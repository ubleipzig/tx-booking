<?php
/**
 * Class Week
 *
 * Copyright (C) Leipzig University Library 2017 <info@ub.uni-leipzig.de>
 *
 * @author  Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

namespace LeipzigUniversityLibrary\UblBooking\Library;

/**
 * Class Week
 *
 * @package LeipzigUniversityLibrary\UblBooking\Library
 */
class Week extends DateHelper implements \Iterator, \Countable {

	/**
	 * When the average day starts
	 *
	 * @var integer
	 */
	protected $dayStart;

	/**
	 * When the average day ends
	 *
	 * @var integer
	 */
	protected $dayEnd;

	/**
	 * The current time of the week iteration
	 *
	 * @var \DateTime
	 */
	protected $current;

	/**
	 * Week constructor.
	 *
	 * @param int $timestamp [optional] the unix timestamp to create the week from
	 */
	public function __construct($timestamp = null) {
		parent::__construct($timestamp);

		$this->origin = $this->origin->modify('Monday this week');
		$this->current = $this->origin;
	}

	/**
	 * Returns the current iteration value
	 *
	 * @return \LeipzigUniversityLibrary\UblBooking\Library\Day
	 */
	public function current() {
		return new Day($this->current->getTimestamp(), $this->dayStart, $this->dayEnd);
	}

	/**
	 * returns the current iteration key
	 *
	 * @return string
	 */
	public function key() {
		return $this->current->format('N');
	}

	/**
	 * Iterate to next
	 */
	public function next() {
		$this->current = $this->current->modify('next day');
	}

	/**
	 * Returns whether next is valid
	 *
	 * @return bool
	 */
	public function valid() {
		return ($this->current->format('W') === $this->origin->format('W'));
	}

	/**
	 * Reset iteration
	 */
	public function rewind() {
		$this->current = $this->origin;
	}

	/**
	 * sets the first hour of the day
	 *
	 * @param $value
	 */
	public function setDayStart($value) {
		$this->dayStart = (int)$value;
	}

	/**
	 * sets the last hour of the day
	 *
	 * @param $value
	 */
	public function setDayEnd($value) {
		$this->dayEnd = (int)$value;
	}

	/**
	 * Return start time of the week
	 *
	 * @return \DateTimeImmutable
	 */
	public function getStart() {
		return $this->origin->modify('Monday this week');
	}

	/**
	 * return end time of the week
	 *
	 * @return \DateTimeImmutable
	 */
	public function getEnd() {
		return $this->origin->modify('Monday next week -1 second');
	}

	/**
	 * return count of week days
	 *
	 * @return int
	 */
	public function count() {
		return 7;
	}
}