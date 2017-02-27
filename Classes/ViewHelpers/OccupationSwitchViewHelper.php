<?php
namespace LeipzigUniversityLibrary\Ublbooking\ViewHelpers;

use \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\Ublbooking\Library\Hour;

class OccupationSwitchViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\Ublbooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, \LeipzigUniversityLibrary\Ublbooking\Library\Hour $hour) {
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