<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\ViewHelpers;

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;

class GetBookingCommentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param \DateTimeInterface $timestamp
	 * @return string
	 */
	public function render(Room $room, \DateTimeInterface $timestamp) {
		$room->getBooking($timestamp)->getComment();

	}
}