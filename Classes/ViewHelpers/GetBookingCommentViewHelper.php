<?php
/**
 * Class GetBookingCommentViewHelper
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
 * Class GetBookingCommentViewHelper
 *
 * @package LeipzigUniversityLibrary\UblBooking\ViewHelpers
 */
class GetBookingCommentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * Returns the comment of a booking
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param \DateTimeInterface $timestamp
	 * @return string
	 */
	public function render(Room $room, \DateTimeInterface $timestamp) {
		return $room->getBooking($timestamp)->getComment();
	}
}