<?php
namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;

class BookableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 * @param optional \LeipzigUniversityLibrary\UblBooking\Library\Day $day
	 * @param optional \LeipzigUniversityLibrary\UblBooking\Library\Hour $hour
	 * @return string
	 */
	public function render(Room $room, Day $day = null, Hour $hour = null) {
		$result = false;
		if ($day) {
			$result = $room->isDayBookable($day);
		}

		if ($hour) {
			$result = $room->isHourBookable($hour);
		}

		if ($result) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}