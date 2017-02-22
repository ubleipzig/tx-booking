<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\ViewHelpers;

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;

class GetHourOccupationClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, Hour $hour) {
		switch ($room->getHourOccupation($hour)) {
			case Room::OFFDUTY:
				return 'offDutyTimeHours';
			case Room::AVAILABLE:
				return 'dutyHours';
			case Room::FOREIGNBOOKED:
				return 'bookedHours';
			case Room::OWNBOOKED:
				return 'ownbookedHours';
		}
	}
}