<?php
namespace LeipzigUniversityLibrary\Ublbooking\ViewHelpers;

use \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;
use \LeipzigUniversityLibrary\Ublbooking\Library\Day;
use \LeipzigUniversityLibrary\Ublbooking\Library\Hour;

class BookableViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room $room
	 * @param optional \LeipzigUniversityLibrary\Ublbooking\Library\Day $day
	 * @param optional \LeipzigUniversityLibrary\Ublbooking\Library\Hour $hour
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