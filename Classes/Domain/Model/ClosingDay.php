<?php
/**
 * Class ClosingDay
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
use \Ubl\Booking\Library\Day;

/**
 * Class ClosingDay
 *
 * @package Ubl\Booking\Domain\Model
 */
class ClosingDay extends AbstractEntity {

	/**
	 * the date of the closing day
	 *
	 * @var integer
	 **/
	protected $date;

	/**
	 * The name of the closing day
	 *
	 * @var string
	 **/
	protected $name;

	/**
	 * The description of the closing day
	 *
	 * @var string
	 **/
	protected $description;

	/**
	 * the Day representation of the closing day
	 *
	 * @var \Ubl\Booking\Library\Day
	 */
	protected $day;

	/**
	 * ClosingDay constructor.
	 *
	 * @param integer $date the date as unix timestamp
	 * @param string  [optional] $name the name
	 * @param string  [optional] $description the description
	 */
	public function __construct($date, $name = '', $description = '') {
		$this->setDate($date);
		$this->setName($name);
		$this->setDescription($description);
	}

	/**
	 * returns the Day representation of the closing day
	 *
	 * @return \Ubl\Booking\Library\Day
	 */
	public function getDay() {
		if (!$this->day) $this->setDay(new Day($this->date));

		return $this->day;
	}
}
