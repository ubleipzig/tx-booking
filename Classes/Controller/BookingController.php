<?php
/**
 * Class BookingController
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

namespace LeipzigUniversityLibrary\UblBooking\Controller;

use \LeipzigUniversityLibrary\UblBooking\Library\Week;
use \LeipzigUniversityLibrary\UblBooking\Library\Day;
use \LeipzigUniversityLibrary\UblBooking\Library\Hour;
use \LeipzigUniversityLibrary\UblBooking\Domain\Model\Booking;

/**
 * Class BookingController
 *
 * The main controller, holding all action methods
 *
 * @package LeipzigUniversityLibrary\UblBooking\Controller
 */
class BookingController extends AbstractController {

	/**
	 * The repository of bookings
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * the repository of rooms
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Room
	 * @inject
	 */
	protected $roomRepository;

	/**
	 * Shows the overview of a day for a room
	 *
	 * @param integer                                                $timestamp
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room
	 */
	public function showDayAction($timestamp, $room) {
		$today = new Day();
		$day = new Day($timestamp);
		$room->fetchDayOccupation($day);
		$room->setSettingsHelper($this->settingsHelper);
		$this->view->assign('Room', $room);

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showNextDay($day)) {
			$this->view->assign('nextDay', $room->getDay()->modify('next day')->getTimestamp());
		}

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showPreviousDay($day)) {
			$this->view->assign('previousDay', $room->getDay()->modify('previous day')->getTimestamp());
		}
	}

	/**
	 * shows the overview of a week for a room
	 *
	 * @param integer $timestamp [optional] the timestamp of the week. if omitted the current week is taken.
	 *                           the timestamp is always converted to 00:00 on the first day of the week.
	 */
	public function showWeekAction($timestamp = null) {
		$week = new Week($timestamp);

		$rooms = $this->roomRepository->findAllWithOccupationForWeek($week, $this->settingsHelper);
		$today = new Day();
		$now = new \DateTimeImmutable('now', new \DateTimeZone(date_default_timezone_get()));


		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showNextWeek($week)) {
			$this->view->assign('nextWeek', $week->modify('Monday next week')->getTimestamp());
		}

		if ($this->settingsHelper->isAdmin() || $this->settingsHelper->showPreviousWeek($week)) {
			$this->view->assign('previousWeek', $week->modify('Monday last week')->getTimestamp());
		}
		$this->view->assign('Today', $today);
		$this->view->assign('Now', $now);
		$this->view->assign('Rooms', $rooms);
	}

	/**
	 * adds a booking to a room for a user
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room      the room to add the booking for
	 * @param integer                                                $timestamp the timestamp of the hour
	 * @param string                                                 $comment   the comment of the booking
	 */
	public function addAction($room, $timestamp, $comment) {
		$hour = new Hour($timestamp);
		$day = new Day($timestamp);
		$today = new Day();
		$room->fetchDayOccupation($day);
		$rooms = $this->roomRepository->findAll();

		if ($day->getDateTime() < $today->getDateTime()) {
			$this->addFlashMessage('bookingInPast', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (!$this->settingsHelper->isAdmin() && $this->settingsHelper->exceededBookingLimit($timestamp)) {
			$this->addFlashMessage('bookingInFuture', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (!$room->isHourBookable($hour)) {
			$this->addFlashMessage('alreadyBookedByForeignUser', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if (count($this->bookingRepository->findByUserAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $hour->getDateTime())) > 0) {
			$this->addFlashMessage('alreadyBookedInOtherRoom', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if ($this->settingsHelper->getMaxBookings() && count($this->bookingRepository->findByUserAndRoomsAndBetween($GLOBALS['TSFE']->fe_user->user['uid'], $rooms, $day->getStart(), $day->getEnd())) === $this->settingsHelper->getMaxBookings()) {
			$this->addFlashMessage('maxBookingsReached', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else {
			$this->bookingRepository->add(new Booking($timestamp, $room, $comment));
			$this->addFlashMessage('successfullyBooked', \TYPO3\CMS\Core\Messaging\FlashMessage::OK);
		}

		$this->redirect('showDay', 'Booking', null, ['timestamp' => $timestamp, 'room' => $room]);
	}

	/**
	 * removes a booking from a room for a user
	 *
	 * @param \LeipzigUniversityLibrary\UblBooking\Domain\Model\Room $room      the room to remove the booking for
	 * @param integer                                                $timestamp the timestamp of the hour to remove
	 */
	public function removeAction($room, $timestamp) {
		$hour = new Hour($timestamp);
		$day = new Day($timestamp);
		$room->fetchDayOccupation($day);
		$now = new Hour();

		// booking in the past, do not allow to remove
		if ($now->getDateTime() > $hour->getDateTime()) {
			$this->addFlashMessage('cannotRemovePastBooking', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		} else if ($booking = $this->bookingRepository->findByUserAndRoomAndTime($GLOBALS['TSFE']->fe_user->user['uid'], $room, $hour->getDateTime())->getFirst()) {
			$this->addFlashMessage('bookingSuccessfullyRemoved');
			$this->bookingRepository->remove($booking);
		} else {
			$this->addFlashMessage('noBookingToRemove', \TYPO3\CMS\Core\Messaging\FlashMessage::ERROR);
		}

		$this->redirect('showDay', 'Booking', null, ['timestamp' => $timestamp, 'room' => $room]);
	}
}
