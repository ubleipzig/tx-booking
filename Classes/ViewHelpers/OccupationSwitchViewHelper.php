<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\ViewHelpers;

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;

class OccupationSwitchViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour $hour) {
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