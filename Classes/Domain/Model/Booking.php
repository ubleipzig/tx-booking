<?php
/**
 * Class Booking
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

namespace Ubl\Booking\Domain\Model;

use \Ubl\Booking\Library\AbstractEntity;

/**
 * Class Booking
 *
 * @package Ubl\Booking\Domain\Model
 */
class Booking extends AbstractEntity
{
	/**
	 * Frontend user id
	 *
	 * @var integer
	 */
	protected $feUser;

	/**
	 * Room of this booking
	 *
	 * @var \Ubl\Booking\Domain\Model\Room
	 */
	protected $room;

	/**
	 * The comment of the booking
	 *
	 * @var string
	 */
	protected $comment;

	/**
	 * The timestamp of the booking
	 *
	 * @var integer
	 */
	protected $time;

	/**
	 * The DateTime representation of the booking time
	 *
	 * @var \DateTimeImmutable
	 */
	private $dateTime;

	/**
	 * Booking constructor.
	 *
	 * @param        $timestamp Timestamp of booking
	 * @param        $room      Room which is booked
	 * @param string $comment   [optional] Comment of booking
	 * @throws \Exception If we want to create a booking without a logged user
	 */
	public function __construct($timestamp, $room, $comment = '')
    {
		if (!$GLOBALS['TSFE']->fe_user->user) {
            throw new \Exception('no user found');
        }
		$this->initializeObject();
		$this->setTime($timestamp);
		$this->setRoom($room);
		$this->setComment($comment);
		$this->setFeUser($GLOBALS['TSFE']->fe_user->user['uid']);

		if ($room->getBookingStorage()) {
			$this->setPid($room->getBookingStorage());
		}
	}

	/**
	 * Initializes the model after creation with constructor or via DI (which is creating te object without invoking the constructor)
	 */
	public function initializeObject()
    {
		$this->dateTime = new \DateTimeImmutable('now', new \DateTimeZone(date_default_timezone_get()));
	}

	/**
	 * returns the DateTime representation of the bookings unix timestamp
	 *
	 * @return bool|\DateTimeImmutable
	 */
	public function getDateTime()
    {
		return $this->dateTime->setTimestamp($this->time);
	}
}