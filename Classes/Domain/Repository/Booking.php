<?php
/**
 * Class Booking
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

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Repository;
use Ubl\Booking\Domain\Model\Room as RoomModel;

/**
 * Class Booking
 *
 * @package Ubl\Booking\Domain\Repository
 */
class Booking extends Repository
{
    /**
     * Name of table
     *
     * @var string $tableName
     * @access protected
     */
    protected $tableName = 'tx_booking_domain_model_booking';

    /**
	 * Booking constructor.
	 *
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager)
    {
		parent::__construct($objectManager);
		$this->initializeObject();
	}

    /**
     * Get connection for table
     *
     * @param string $tbl	Table name
     *
     * @access protected
     */
    protected function getConnectionForTable($tbl)
    {
        /** @var ConnectionPool $connectionPool */
        return GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($tbl);
    }

    /**
	 * initializes the repository object by removing the pid constraint from default query settings
	 */
	public function initializeObject()
    {
		$querySettings = $this->objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(false);
		$this->setDefaultQuerySettings($querySettings);
	}

	/**
	 * Finds all Bookings by specified room and between specified start- and end-time
	 *
	 * @param \Ubl\Booking\Domain\Model\Room $room
	 * @param \DateTimeInterface $startTime
	 * @param \DateTimeInterface $endTime
     *
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByRoomAndBetween(RoomModel $room, \DateTimeInterface $startTime, \DateTimeInterface $endTime)
    {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->greaterThanOrEqual('time', $startTime->getTimestamp()),
			$query->lessThanOrEqual('time', $endTime->getTimestamp()),
			$query->equals('room', $room)
		]);
		$query->matching($where);

		return $query->execute();
	}

	/**
	 * Finds a booking by specified user and time. One booking per user only and time is allowed.
     * Therefor this should return 0 or 1
	 *
	 * @param object $user User's uid
	 * @param \DateTimeInterface $startTime Time
     *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUserAndTime($user, \DateTimeInterface $startTime)
    {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);
		return $query->execute();
	}

	/**
	 * Finds a booking for specified user, room and time. Should return 0 or 1
	 *
	 * @param object $user User
	 * @param \Ubl\Booking\Domain\Model\Room $room Room
	 * @param \DateTimeInterface $startTime Start time
     *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUserAndRoomAndTime($user, RoomModel $room, \DateTimeInterface $startTime)
    {
		$query = $this->createQuery();
		$where = $query->logicalAnd([
			$query->equals('time', $startTime->getTimestamp()),
			$query->equals('room', $room),
			$query->equals('fe_user', $user)
		]);
		$query->matching($where);

		return $query->execute();
	}

	/**
	 * Finds all bookings for specified user in specified rooms for a specified time period
	 *
	 * @param object $user      User
	 * @param object $rooms     Rooms
	 * @param \DateTimeInterface $startTime Start time
	 * @param \DateTimeInterface $endTime   End time
     *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
	 */
	public function findByUserAndRoomsAndBetween(
        $user,
        $rooms,
        \DateTimeInterface $startTime,
        \DateTimeInterface $endTime
    ) {
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

	/**
	 * Finds all bookings before a specified time
	 *
	 * @param \DateTimeInterface $time Time
     *
	 * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface the result
	 */
	public function findBeforeTime(\DateTimeInterface $time)
    {
        $queryBuilder = $this->getConnectionForTable($this->tableName);
        $queryBuilder->getRestrictions()->removeAll();
        return $queryBuilder
            ->select('uid')
            ->from($this->tableName)
            ->where(
                $queryBuilder->expr()->lt(
                    'time',
                    $queryBuilder->quote($time->getTimestamp(), \PDO::PARAM_INT)
                )
            )
            ->execute()
            ->fetchAll()
        ;
	}

    /**
     * Remove bookings by id
     *
     * @param array $uids	Amount of uid to remove
     *
     * @return int 	Affected row to proceed.
     * @access public
     */
    public function removeUsersByIds(array $uids) : int
    {
        $cnt = 0;
        try {
            $deleteList = implode(
                ', ',
                array_map(function ($item) {
                    return "'" . $item . "'";
                },
                    $uids)
            );
            $queryBuilder = $this->getConnectionForTable($this->tableName);
            $cnt = $queryBuilder
                ->delete($this->tableName)
                ->where(
                    $queryBuilder->expr()->in('uid', $deleteList)
                )
                ->execute();
        } catch (\Exception $e) {
            'Error while operating on database:' . $e->getMessage() . ' with stack trace: ' . $e->getTraceAsString();
        }
        return $cnt;
    }
}
