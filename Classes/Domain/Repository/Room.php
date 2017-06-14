<?php
/**
 * Class Room
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

/**
 * Class Room
 *
 * @package Ubl\Booking\Domain\Repository
 */
class Room extends Repository {

	/**
	 * Finds all rooms and fetch their occupation for specified week
	 *
	 * @param \Ubl\Booking\Library\Week           $week           the week
	 * @param \Ubl\Booking\Library\SettingsHelper $settingsHelper the settings helper
	 * @return \TYPO3\CMS\Extbase\Persistence\QueryResultInterface the result
	 */
	public function findAllWithOccupationForWeek(\Ubl\Booking\Library\Week $week, \Ubl\Booking\Library\SettingsHelper $settingsHelper) {
		$result = $this->findAll();
		foreach ($result as $room) {
			$room->fetchWeekOccupation($week);
			$room->setSettingsHelper($settingsHelper);
		}

		return $result;
	}
}