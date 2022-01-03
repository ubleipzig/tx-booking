<?php
/**
 * Class SettingsHelper
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

namespace Ubl\Booking\Library;

/**
 * Class SettingsHelper
 *
 * @package Ubl\Booking\Library
 */
class SettingsHelper {

	/**
	 * The settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * SettingsHelper constructor.
	 *
	 * @param array $settings passed by controller method
	 */
	public function __construct($settings) {
		$this->settings = $settings;
	}

	/**
	 * Whether to show next week according to the settings
	 *
	 * @param \Ubl\Booking\Library\Week $week the actual week
	 * @return bool
	 */
	public function showNextWeek(Week $week) {
		if (empty($this->settings['limitBookingToWeeks'])) {
            return true;
        }
		$today = new Week();
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return $week->getDateTime() < $limit;
	}

	/**
	 * Whether to show previous week according to the settings
	 *
	 * @param \Ubl\Booking\Library\Week $week the actual week
	 * @return bool
	 */
	public function showPreviousWeek(Week $week) {
		if (empty($this->settings['limitBacklogToWeeks'])) {
            return true;
        }
		$today = new Week();
		$limit = $today->sub(new \DateInterval("P{$this->settings['limitBacklogToWeeks']}W"));
		return $week->getDateTime() > $limit;
	}

	/**
	 * Whether to show the next day according to the settings
	 *
	 * @param \Ubl\Booking\Library\Day $day the actual day
	 * @return bool
	 */
	public function showNextDay(Day $day) {
		if (empty($this->settings['limitBookingToWeeks'])) {
            return true;
        }
		$today = new Week();
		$nextDay = $day->modify('next day');
		$nextDaysWeek = new Week($nextDay->getTimestamp());
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return !($limit < $nextDaysWeek->getDateTime());
	}

	/**
	 * whether to show the previous day according to the settings
	 *
	 * @param \Ubl\Booking\Library\Day $day the actual day
	 * @return bool
	 */
	public function showPreviousDay(Day $day) {
		if (empty($this->settings['limitBacklogToWeeks'])) {
            return true;
        }
		$today = new Week();
		$previousDay = $day->modify('previous day');
		$previousDaysWeek = new Week($previousDay->getTimestamp());
		$limit = $today->sub(new \DateInterval("P{$this->settings['limitBacklogToWeeks']}W"));
		return !($limit > $previousDaysWeek->getDateTime());
	}

	/**
	 * Whether the provided user is admin
	 *
	 * @param  $user_id [optional] if empty try the currently logged in user
	 * @return bool
	 */
	public function isAdmin($user_id = null) {
		if ($user_id === null && $GLOBALS['TSFE']->fe_user->user['uid']) {
			$user_id = $GLOBALS['TSFE']->fe_user->user['uid'];
		}

		return isset($this->settings['admins']) && isset($user_id)
			? in_array($user_id, explode(',', $this->settings['admins']))
			: false;
	}

	/**
	 * Whether the booking in advance is exceeded by the provided timestamp
	 *
	 * @param int $timestamp the time for the booking
	 * @return bool
	 */
	public function exceededBookingLimit($timestamp) {
		if (empty($this->settings['limitBookingToWeeks'])) {
            return true;
        }
		$today = new Week();
		$week = new Week($timestamp);
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return $week->getDateTime() > $limit;
	}

	/**
	 * Returns the maximum bookings per day and plugin
	 *
	 * @return int|null
	 */
	public function getMaxBookings() {
		return isset($this->settings['maxBookingsPerDay']) ? (int)$this->settings['maxBookingsPerDay'] : null;
	}
}
