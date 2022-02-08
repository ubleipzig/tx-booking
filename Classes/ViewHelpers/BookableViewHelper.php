<?php
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
namespace Ubl\Booking\ViewHelpers;

use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper;
use Ubl\Booking\Domain\Model\Room;
use Ubl\Booking\Library\Day;
use Ubl\Booking\Library\Hour;

/**
 * Class BookableViewHelper
 *
 * @package Ubl\Booking\ViewHelpers
 */
class BookableViewHelper extends AbstractConditionViewHelper
{
    /**
     * Initializes arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('room', Room::class,'\Ubl\Booking\Domain\Model\Room', true);
        $this->registerArgument('day', Day::class,'\Ubl\Booking\Library\Day', false);
        $this->registerArgument('hour', Hour::class,'\Ubl\Booking\Library\Hour', false);
    }

	/**
	 * Whether the room im bookable for either the specified day or hour
	 * be aware that you do not need to specify the day when you specified the hour
	 *
	 * @return string
	 */
	public function render()
    {
        $room = $this->arguments['room'];
        $day = $this->arguments['day'] ?? null;
        $hour = $this->arguments['hour'] ?? null;
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