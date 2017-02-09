<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\AbstractEntity;

class Booking extends AbstractEntity {

	/**
	 * frontend user id
	 *
	 * @var integer
	 */
	protected $user;

	/**
	 *
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room
	 */
	protected $room;

	/**
	 * timestamp of the booking
	 *
	 * @var integer
	 */
	protected $timestamp;
}