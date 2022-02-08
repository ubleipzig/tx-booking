<?php
/**
 * Class Day
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
namespace Ubl\Booking\Library;

/**
 * Class Day
 *
 * @package Ubl\Booking\Library
 */
class Day extends DateHelper implements \Iterator, \Countable
{
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

	/**
	 * Day constructor.
	 *
	 * @param null $timestamp [optional] the unix timestamp to create the day from. if omitted it is today
	 * @param int  $start     [optional] the first hour of the day
	 * @param int  $end       [optional] the last hour of the day
	 * @throws \Exception
	 */
	public function __construct($timestamp = null, $start = 0, $end = 23)
    {
		parent::__construct($timestamp);

		if ($start > $end) {
            throw new \Exception('start must not be greater than end');
        }
		$this->setStart($start);
		$this->setEnd($end);

		$this->origin = $this->origin->modify('midnight');
		$this->current = $this->origin;
	}

	/**
	 * Returns the current iteration value
	 *
	 * @return \Ubl\Booking\Library\Hour
	 */
	public function current()
    {
		return new Hour($this->current->getTimestamp());
	}

	/**
	 * Returns the current iteration key
	 *
	 * @return int
	 */
	public function key()
    {
		return (int)$this->current->format('H');
	}

	/**
	 * Iterate to next
	 */
	public function next()
    {
		$this->current = $this->current->modify('next hour');
	}

	/**
	 * Reset iteration
	 */
	public function rewind()
    {
		$this->current = $this->origin;
		if ($this->start > 0) {
            $this->current = $this->current->add(new \DateInterval("PT{$this->start}H"));
        }
	}

	/**
	 * Returns whether next is valid
	 *
	 * @return bool
	 */
	public function valid()
    {
		return (($this->current->format('d') === $this->origin->format('d'))
			&& ((int)$this->current->format('H') <= $this->end));
	}

	/**
	 * Returns title of the day
	 *
	 * @return string
	 */
	public function getTitle()
    {
		return $this->origin->format('d.m.Y');
	}

	/**
	 * Sets first hour of the day
	 *
	 * @param int $value
	 */
	public function setStart($value)
    {
		$this->start = (int)$value;
	}

	/**
	 * Sets last hour of the day
	 *
	 * @param int $value
	 */
	public function setEnd($value)
    {
		$this->end = (int)$value;
	}

	/**
	 * Returns first hour of the day
	 *
	 * @return \DateTimeImmutable
	 */
	public function getStart()
    {
		return $this->origin->add(new \DateInterval("PT{$this->start}H"));
	}

	/**
	 * Returns last hour fo the day
	 *
	 * @return \DateTimeImmutable
	 */
	public function getEnd()
    {
		return $this->origin->add(new \DateInterval("PT{$this->end}H"));
	}

	/**
	 * returns count of hours for the day
	 *
	 * @return int
	 */
	public function count()
    {
		return $this->end - $this->start;
	}
}