<?php
/**
 * Class CleanupCommandController
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

namespace Ubl\Booking\Command;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use \TYPO3\CMS\Extbase\Mvc\Controller\CommandController;
use Ubl\Booking\Library\Week;

/**
 * Class CleanupCommandController
 *
 * Provides commandline interface to cleanup past bookings
 *
 * @package Ubl\Booking\Command
 */
class CleanupCommandController extends CommandController
{
	/**
	 * Repository of bookings
	 *
	 * @var \Ubl\Booking\Domain\Repository\Booking
	 * @Extbase\Inject
	 */
	protected $bookingRepository;

	/**
	 * Removes old bookings
	 *
	 * @param int $weeks How many weeks to keep. If empty all bookings from previous week are removed
     *
     * @return void
	 */
	public function cleanupBookingsCommand($weeks = 0)
    {
		$today = new Week();
		$time = $today->sub(new \DateInterval("P{$weeks}W"));

		$bookings = $this->bookingRepository->findBeforeTime($time);
		foreach ($bookings as $booking) {
			$this->bookingRepository->remove($booking);
		}
		$this->output('%d bookings removed before %s', [count($bookings), $time->format('d-m-y H:i:s T (e, \G\M\T P)')]);
	}
}
