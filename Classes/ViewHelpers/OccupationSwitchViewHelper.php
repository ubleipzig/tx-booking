<?php
/**
 * Class OccupationSwitchViewHelper
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

namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

/**
 * Class OccupationSwitchViewHelper
 *
 * @package LeipzigUniversityLibrary\UblBooking\ViewHelpers
 */
class OccupationSwitchViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper {

	/**
	 * Switch to render according to occupation of room and hour
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, Hour $hour) {
		switch ($room->getHourOccupation($hour)) {
			case Room::OFFDUTY:
				return parent::render('offDuty');
			case Room::AVAILABLE:
				return parent::render('available');
			case Room::FOREIGNBOOKED:
				return parent::render('foreignBooked');
			case Room::OWNBOOKED:
				return parent::render('ownBooked');
		}
	}
}