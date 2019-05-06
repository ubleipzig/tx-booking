<?php
namespace Ubl\Booking\ViewHelpers;
/**
 * Class BookableViewHelper
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

use \Ubl\Booking\Domain\Model\Room;
use \Ubl\Booking\Library\Day;
use \Ubl\Booking\Library\Hour;

/**
 * Class BookableViewHelper
 *
 * @package Ubl\Booking\ViewHelpers
 */
class BookableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Whether the room im bookable for either the specified day or hour
	 * be aware that you do not need to specify the day when you specified the hour
	 *
	 * @param \Ubl\Booking\Domain\Model\Room $room
	 * @param optional \Ubl\Booking\Library\Day $day
	 * @param optional \Ubl\Booking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, Day $day = null, Hour $hour = null) {
		$result = false;
		if ($day) {
			$result = $room->isDayBookable($day);
		}

		if ($hour) {
			$result = $room->isHourBookable($hour);
		}

		if ($result) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}