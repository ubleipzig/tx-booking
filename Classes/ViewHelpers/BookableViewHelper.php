<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\ViewHelpers;

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour;

class BookableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room $room
	 * @param optional \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day $day
	 * @param optional \LeipzigUniversityLibrary\ubleipzigbooking\Library\Hour $hour
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