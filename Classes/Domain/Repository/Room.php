<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class Room extends Repository {
	public function findAllBetween(\DateTime $start, \DateTime $end) {
	}

}