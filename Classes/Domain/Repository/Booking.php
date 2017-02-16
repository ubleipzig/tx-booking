<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;

class Booking extends Repository {

	public function findByRoomAndBetween(Room $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (startdate)
			$query->greaterThanOrEqual('startdate', $startTime->getTimestamp()),
			$query->lessThanOrEqual('startdate', $endTime->getTimestamp()),
			$query->equals('room', $room)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndTime($user, \DateTimeInterface $startTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (startdate)
			$query->equals('startdate', $startTime->getTimestamp()),
			$query->equals('user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndRoomAndTime($user, Room $room, \DateTimeInterface $startTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (startdate)
			$query->equals('startdate', $startTime->getTimestamp()),
			$query->equals('room', $room),
			$query->equals('user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndBetween($user, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('startdate', $startTime->getTimestamp()),
			$query->lessThanOrEqual('startdate', $endTime->getTimestamp()),
			$query->equals('user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}
}