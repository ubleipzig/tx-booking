<?php
namespace LeipzigUniversityLibrary\Ublbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class Room extends Repository {
	public function findAllWithOccupationForWeek($week, $settingsHelper) {
	$result = $this->findAll();
		foreach ($result as $room) {
			$room->fetchWeekOccupation($week);
			$room->setSettingsHelper($settingsHelper);
		}

		return $result;
	}
}