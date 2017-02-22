<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;

class ClosingDay extends Repository {

	public function findByRoomAndBetween(Room $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('date', $startTime->getTimestamp()),
			$query->lessThanOrEqual('date', $endTime->getTimestamp())
		]);
		$query->matching($where);

		$result = $query->execute();

		return $this->reduceResult($result, $query->getQuerySettings()->getStoragePageIds())[0];
	}

	public function findByRoomAndDay(Room $room, \DateTimeInterface $day) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$query->matching($query->equals('date', $day->getTimestamp()));

		$result = $query->execute();

		return $this->reduceResult($result, $query->getQuerySettings()->getStoragePageIds())[0];
	}

	protected function reduceResult($result, $storagePids) {
		$dutyHours = [];

		foreach ($storagePids as $pid) {
			foreach ($result as $dset) {
				$weekDay = $dset->getWeekDay();
				if ($dset->getPid() !== $pid || $dutyHours[$weekDay]) continue;
				$dutyHours[$weekDay] = $dset;
			}
		}

		return $dutyHours;
	}
}