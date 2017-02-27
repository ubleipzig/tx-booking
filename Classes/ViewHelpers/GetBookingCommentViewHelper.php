<?php
namespace LeipzigUniversityLibrary\Ublbooking\ViewHelpers;

use \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\Ublbooking\Library\Hour;

class GetBookingCommentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room $room
	 * @param \DateTimeInterface $timestamp
	 * @return string
	 */
	public function render(Room $room, \DateTimeInterface $timestamp) {
		return $room->getBooking($timestamp)->getComment();
	}
}