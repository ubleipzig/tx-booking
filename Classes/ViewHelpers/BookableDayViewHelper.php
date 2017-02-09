<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\ViewHelpers;

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;

class BookableDayViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	/**
	 * @param object $room
	 * @param object $day
	 * @return string
	 */
	public function render($room, $day) {
		return $this->renderThenChild();
	}
}