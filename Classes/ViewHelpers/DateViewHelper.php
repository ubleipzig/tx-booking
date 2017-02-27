<?php
namespace LeipzigUniversityLibrary\UblBooking\ViewHelpers;

class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param string $format
	 * @param object $object
	 * @param optional string $modify
	 * @return string
	 */
	public function render($format, $object, $modify = null) {
		if ($modify) {
			$object = $object->modify($modify);
		}
		return $object->format($format);
	}

}