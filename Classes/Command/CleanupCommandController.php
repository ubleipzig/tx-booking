<?php
namespace LeipzigUniversityLibrary\UblBooking\Command;

use LeipzigUniversityLibrary\UblBooking\Library\Week;

class CleanupCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {

	/**
	 * the Repository for bookings
	 *
	 * @var \LeipzigUniversityLibrary\UblBooking\Domain\Repository\Booking
	 * @inject
	 */
	protected $bookingRepository;

	/**
	 * removes old bookings
	 *
	 * @param int $weeks How many weeks to keep. If empty all bookings from previous week are removed
	 */
	public function cleanupBookingsCommand($weeks = 0) {
		$today = new Week();
		$time = $today->sub(new \DateInterval("P{$weeks}W"));

		$bookings = $this->bookingRepository->findBeforeTime($time);
		foreach($bookings as $booking) {
			$this->bookingRepository->remove($booking);
		}

		$this->output('%d bookings removed before %s', [count($bookings), $time->format('d-m-y H:i:s T (e, \G\M\T P)')]);
	}
}
