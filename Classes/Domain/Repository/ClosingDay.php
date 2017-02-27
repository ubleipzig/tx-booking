<?php
namespace LeipzigUniversityLibrary\UblBooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room as RoomModel;

class ClosingDay extends Repository {

	public function findByRoomAndBetween(RoomModel $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('date', $startTime->getTimestamp()),
			$query->lessThanOrEqual('date', $endTime->getTimestamp())
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByRoomAndDay(RoomModel $room, \DateTimeInterface $day) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$query->matching($query->equals('date', $day->getTimestamp()));

		return $query->execute()->getFirst();
	}
}