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

namespace Ubl\Booking\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use Ubl\Booking\Domain\Model\Room;

/**
 * Class GetBookingCommentViewHelper
 *
 * @package Ubl\Booking\ViewHelpers
 */
class GetBookingCommentViewHelper extends AbstractViewHelper
{
    /**
     * Initializes arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('room', Room::class,'\Ubl\Booking\Domain\Model\Room', true);
        $this->registerArgument('timestamp', \DateTimeInterface::class,'\DateTimeInterface', true);
    }

	/**
	 * Returns the comment of a booking
	 *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $room = $arguments['room'];
        $timestamp = $arguments['timestamp'];
		return $room->getBooking($timestamp)->getComment();
	}
}