<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class Room extends Repository {
	public function findAllWithOccupationForWeek($week, $settings) {
		$result = $this->findAll();
		foreach ($result as $room) {
			$room->fetchWeekOccupation($week);
			$room->setSettings($settings);
		}

		return $result;
	}
}