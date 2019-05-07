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
		'tx_ublbooking_domain_model_room' => 'tx_booking_domain_model_room'
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
			if (!$this->fixTableNames()) return $this->databaseConnection->sql_error;
			$result .= '<p>renamed old table names</p>';
		}

		return $result;
	}

	public function access() {
		return true;
	}

	/**
	 * tests whether tables with old names exist
	 *
	 * returns boolean
	 */
	protected function hasOldTables() {
		$allTables = $this->databaseConnection->admin_get_tables();
		if (count(array_intersect(array_keys($this->oldToNewTables), array_keys($allTables))) === count($this->oldToNewTables)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * renames tables with old names
	 *
	 * returns boolean
	 */
	protected function fixTableNames() {
		foreach ($this->oldToNewTables as $oldTable => $newTable) {
			if (!$this->databaseConnection->sql_query(sprintf('DROP TABLE IF EXISTS `%s`', $newTable))) return false;
			if (!$this->databaseConnection->sql_query(sprintf('ALTER TABLE `%s` RENAME `%s`', $oldTable, $newTable))) return false;
		}

		return true;
	}
}