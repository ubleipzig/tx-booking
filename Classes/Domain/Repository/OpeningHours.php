<?php
/**
 * Class OpeningHours
 *
 * Copyright (C) Leipzig University Library 2017 <info@ub.uni-leipzig.de>
 *
 * @author  Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

namespace LeipzigUniversityLibrary\UblBooking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room as RoomModel;

/**
 * Class OpeningHours
 *
 * @package LeipzigUniversityLibrary\UblBooking\Domain\Repository
 */
class OpeningHours extends Repository {

	/**
	 * The default ordering for queries
	 *
	 * @var array
	 */
	protected $defaultOrderings = [
		'week_day' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING,
		'hours' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_DESCENDING
	];

	/**
	 * Finds the opening hours for specified room and day
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room the room
	 * @param \DateTimeInterface                                     $day the day
	 * @return array the result
	 */
	public function findByRoomAndDay(RoomModel $room, \DateTimeInterface $day) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$query->matching($query->equals('week_day', $day->format('N')));

		return $this->reduceResult($query->execute(), $query->getQuerySettings()->getStoragePageIds());
	}

	/**
	 * Finds all opening hours for specified room
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room the room
	 * @return array the result
	 */
	public function findAllByRoom(RoomModel $room) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		return $this->reduceResult($query->execute(), $query->getQuerySettings()->getStoragePageIds());
	}

	/**
	 * Finds all opening hours
	 *
	 * @return array the result
	 */
	public function findAll() {
		$query = $this->createQuery();
		$result  = $query->execute();
		return $this->reduceResult($result, $query->getQuerySettings()->getStoragePageIds());
	}

	/**
	 * Aggregates the result to omit overridden entries.
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\QueryResultInterface $result the result to reduce
	 * @param $storagePids the storage pids in order
	 * @return array the result
	 */
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