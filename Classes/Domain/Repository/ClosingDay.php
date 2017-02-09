<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class ClosingDay extends Repository {

	public function findAllBetween(\DateTime $startTime, \DateTime $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('date', $startTime->getTimestamp()),
			$query->lessThanOrEqual('date', $endTime->getTimestamp())
		]);
		$query->matching($where);

		return $query->execute();
	}
}