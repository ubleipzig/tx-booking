<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;

class ClosingDay extends Repository {
	public function findByWeek(\DateTime $dateTime) {
		$start = $dateTime->modify('Monday this week')->getTimestamp();
		$end = $dateTime->modify('Sunday next week')->getTimestamp();

		$query = $this->createQuery();
		$query->getQuerySettings()->useQueryCache(false);
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('date', $start),
			$query->lessThanOrEqual('date', $end)
		]);
		$query->matching($where);

		return $query->execute(true);
	}

	public function findAll() {
		return $this->findByWeek(new \DateTime('2017-02-05'));
	}
}