<?php
namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

class GetHourOccupationClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, Hour $hour) {
		switch ($room->getHourOccupation($hour)) {
			case Room::OFFDUTY:
				return 'offDutyTimeHours';
			case Room::AVAILABLE:
				return 'openingHours';
			case Room::FOREIGNBOOKED:
				return 'bookedHours';
			case Room::OWNBOOKED:
				return 'ownbookedHours';
		}
	}
}