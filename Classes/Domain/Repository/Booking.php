<?php
namespace LeipzigUniversityLibrary\UblBooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room as RoomModel;

class Booking extends Repository {

	public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager) {
		parent::__construct($objectManager);
		$this->initializeObject();
	}

	public function initializeObject() {
		$querySettings = $this->objectManager->get('\TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(false);
		$this->setDefaultQuerySettings($querySettings);
	}

	public function findByRoomAndBetween(RoomModel $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
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
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndRoomAndTime($user, RoomModel $room, \DateTimeInterface $startTime) {
		$query = $this->createQuery();

		$where = $query->logicalAnd([
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('room', $room),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findByUserAndRoomsAndBetween($user, $rooms, \DateTimeInterface $startTime, \DateTimeInterface $endTime) {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('time', $startTime->getTimestamp()),
			$query->lessThanOrEqual('time', $endTime->getTimestamp()),
			$query->equals('fe_user', $user),
			$query->in('room', $rooms)
		]);
		$query->matching($where);

		return $query->execute();
	}

	public function findBeforeTime(\DateTimeInterface $time) {
		$query = $this->createQuery();

		$where = $query->lessThan('time', $time->getTimestamp());
		$query->matching($where);

		return $query->execute();
	}
}