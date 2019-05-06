<?php
/**
 * Class ClosingDay
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

namespace Ubl\Booking\Domain\Repository;

use \TYPO3\CMS\Extbase\Persistence\Repository;
use \Ubl\Booking\Domain\Model\Room as RoomModel;

/**
 * Class ClosingDay
 *
 * @package Ubl\Booking\Domain\Repository
 */
class ClosingDay extends Repository {

	/**
	 * Finds all closing days for specified room within specified time period
	 *
	 * @param \Ubl\Booking\Domain\Model\Room $room      the room
	 * @param \DateTimeInterface                                     $startTime the start time
	 * @param \DateTimeInterface                                     $endTime   the end time
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface the result
	 */
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

	/**
	 * Finds all closing days for specified room and day
	 *
	 * @param \Ubl\Booking\Domain\Model\Room $room the room
	 * @param \DateTimeInterface                                     $day  the day
	 * @return \Ubl\Booking\Domain\Model\ClosingDay the result
	 */
	public function findByRoomAndDay(RoomModel $room, \DateTimeInterface $day) {
		$query = $this->createQuery();

		if (count($room->getOpeningTimesStorage()) > 0) {
			$query->getQuerySettings()->setStoragePageIds($room->getOpeningTimesStorage());
		}

		$query->matching($query->equals('date', $day->getTimestamp()));

		return $query->execute()->getFirst();
	}
}