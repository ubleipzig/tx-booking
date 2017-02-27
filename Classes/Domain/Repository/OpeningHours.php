<?php
namespace LeipzigUniversityLibrary\Ublbooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use LeipzigUniversityLibrary\Ublbooking\Domain\Model\Room;

class OpeningHours extends Repository {

	protected $defaultOrderings = [
		'week_day' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
		'hours' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
	];

	public function findByRoomAndDay(Room $room, \DateTimeInterface $day) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$query->matching($query->equals('week_day', $day->format('N')));

		return $this->reduceResult($query->execute(), $query->getQuerySettings()->getStoragePageIds());
	}

	public function findAllByRoom(Room $room) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		return $this->reduceResult($query->execute(), $query->getQuerySettings()->getStoragePageIds());
	}

	public function findAll() {
		$query = $this->createQuery();
		$result  = $query->execute();
		return $this->reduceResult($result, $query->getQuerySettings()->getStoragePageIds());
	}

	protected function reduceResult($result, $storagePids) {
		$openingHours = [];

		foreach ($storagePids as $pid) {
			foreach ($result as $dset) {
				$weekDay = $dset->getWeekDay();
				if ($dset->getPid() !== $pid || $openingHours[$weekDay]) continue;
				$openingHours[$weekDay] = $dset;
			}
		}

		sort($openingHours);
		return $openingHours;
	}
}