<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;

class Booking extends Repository {

	public function findByRoomAndInBetween(\DateTime $startTime, \DateTime $endTime, Room $room) {
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
}