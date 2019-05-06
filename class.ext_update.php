<?php
/**
 * Class ext_update
 *
 * Copyright (C) metaccount.de 2018 <mail@metaccount.de>
 *
 * @author  Ulf Seltmann <ulf.seltmann@metaccount.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
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

namespace Ubl\Booking;

use \Ubl\Booking\Library\Hour;

class ext_update {

	protected $oldToNewTables = [
		'tx_ublbooking_domain_model_booking' => 'tx_booking_domain_model_booking',
		'tx_ublbooking_domain_model_closingday' => 'tx_booking_domain_model_closingday',
		'tx_ublbooking_domain_model_openinghours' => 'tx_booking_domain_model_openinghours',
		'tx_ublbooking_domain_model_room' => 'tx_ublbooking_domain_model_room'
	];

	/** @var \TYPO3\CMS\Core\Database\DatabaseConnection */
	protected $databaseConnection;
	/**
	 * Creates the instance of the class.
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}
	/**
	 * Runs the update.
	 */
	public function main() {
		$result = '';

		if ($this->hasOldTables()) {
			$this->fixTableNames();
			$result .= '<p>renamed old table names</p>';
		}

		if ($this->hasBookingSeriesBookingBreakField()) {
			$this->databaseConnection->sql_query('ALTER TABLE `tx_booking_domain_model_bookingseries` DROP COLUMN `booking_break`');
			$result .= '<p>dropped `tx_booking_domain_model_bookingseries`.`booking_break`</p>';
		}

		$fixedBookingSeries = $this->fixStarts();
		$skipped = 0;
		$updated = 0;
		$removed = 0;
		foreach ($fixedBookingSeries as $bs) {
			$oldStart = new Hour($bs['start']);
			$newStart = new Hour($bs['newStart']);
			switch ($bs['status']) {
				case 'skipped':
					$skipped++;
					break;
				case 'updated':
					$result .= sprintf('<p>fixed %s (%d) to %s (%d) for %s, %s, %s</p>', $oldStart->format('G:i d.m.Y'),$bs['start'], $newStart->format('G:i d.m.Y'),$bs['newStart'], $bs['title'], $oldStart->format('l'), $oldStart->format('G:i'));
					$updated++;
					break;
				case 'removed':
					$result .= sprintf('<p><b>removed %s, %s, %s</b></p>', $bs['title'], $oldStart->format('l'), $oldStart->format('G:i'));
					$removed++;
					break;
			}
		}

		$result .= sprintf("fixed %d booking series, removed %d booking series, skipped %d booking series", $updated, $removed, $skipped);
		return $result;
	}

	public function access() {
		return true;
	}

	protected function hasBookingSeriesBookingBreakField() {
		$fields = $this->databaseConnection->admin_get_fields('tx_booking_domain_model_bookingseries');
		return isset($fields['booking_break']);
	}

	protected function fixStarts() {
		$bookingSeries = $this->databaseConnection->exec_SELECTgetRows('*', 'tx_booking_domain_model_bookingseries', '1=1');

		foreach ($bookingSeries as &$bs) {
			$timestamp = (int)$bs['start'];

			$hour = new Hour($timestamp);
			$start = new Hour($hour->modify('first ' . $hour->format('l') . ' of this month ' . $hour->format('G:i'))->getTimestamp());

			if ($start->getTimestamp() === $timestamp) {
				$bs['status'] = 'skipped';
				continue;
			}
			$bs['newStart'] = $start->getTimestamp();

			if (!$this->databaseConnection->exec_UPDATEquery('tx_booking_domain_model_bookingseries', 'uid=' . $bs['uid'], ['start' => $start->getTimestamp()])) {
				//duplicate
				$this->databaseConnection->exec_DELETEquery('tx_booking_domain_model_bookingseries', 'uid='.$bs['uid']);
				$bs['status'] = 'removed';
			} else {
				$bs['status'] = 'updated';
			};

		}
		return $bookingSeries;
	}

	protected function hasOldTables() {
		$allTables = $this->databaseConnection->admin_get_tables();
		if (count(array_intersect(array_keys($this->oldToNewTables), $allTables)) === count($this->oldToNewTables)) {
			return true;
		} else {
			return false;
		}
	}

	protected function fixTableNames() {
		foreach ($this->oldToNewTables as $oldTable => $newTable) {
			$this->databaseConnection->sql_query(sprintf('DROP TABLE `%s`', $newTable));
			$this->databaseConnection->sql_query(sprintf('ALTER TABLE `%s` RENAME `%s`', $oldTable, $newTable));
			$this->databaseConnection->sql_query(sprintf('DROP TABLE`%s`', $oldTable));
		}
	}
}