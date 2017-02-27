<?php
namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

class OccupationSwitchViewHelper extends \TYPO3\CMS\Fluid\ViewHelpers\SwitchViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour) {
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