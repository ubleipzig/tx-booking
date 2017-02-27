<?php
namespace LeipzigUniversityLibrary\Ublbooking\ViewHelpers;

use \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\Ublbooking\Library\Hour;

class GetHourOccupationClassViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\Ublbooking\Library\Hour $hour
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