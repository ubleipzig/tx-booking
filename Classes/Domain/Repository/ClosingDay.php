<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class ClosingDay extends Repository {

	public function findBetween(\DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('date', $startTime->getTimestamp()),
			$query->lessThanOrEqual('date', $endTime->getTimestamp())
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByDay(\DateTimeInterface $day) {
		$query = $this->createQuery();
		$query->matching($query->equals('date', $day->getTimestamp()));

		return $query->execute()->getFirst();
	}
}