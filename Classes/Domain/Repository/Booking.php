<?php
namespace LeipzigUniversityLibrary\Ublbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;

class Booking extends Repository {

	public function findByRoomAndBetween(Room $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (time)
			$query->greaterThanOrEqual('time', $startTime->getTimestamp()),
			$query->lessThanOrEqual('time', $endTime->getTimestamp()),
			$query->equals('room', $room)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndTime($user, \DateTimeInterface $startTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (time)
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndRoomAndTime($user, Room $room, \DateTimeInterface $startTime) {
		$query = $this->createQuery();

		$where = $query->logicalAnd([
			// be aware that the property mapping only works for model arguments (room), not for integer values (time)
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('room', $room),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndBetween($user, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('time', $startTime->getTimestamp()),
			$query->lessThanOrEqual('time', $endTime->getTimestamp()),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function add($booking) {
		parent::add($booking);
	}
}