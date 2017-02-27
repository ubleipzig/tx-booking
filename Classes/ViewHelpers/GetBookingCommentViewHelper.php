<?php
namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

class GetBookingCommentViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param \DateTimeInterface $timestamp
	 * @return string
	 */
	public function render(Room $room, \DateTimeInterface $timestamp) {
		return $room->getBooking($timestamp)->getComment();
	}
}