<?php

namespace LeipzigUniversityLibrary\UblBooking\Library;

class SettingsHelper {

	/**
	 * @var array
	 */
	protected $settings;

	public function __construct($settings) {
		$this->settings = $settings;
	}

	public function showNextWeek($week) {
		if (empty($this->settings['limitBookingToWeeks'])) return true;
		$today = new Week();
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return $week->getDateTime() < $limit;
	}

	public function showPreviousWeek($week) {
		if (empty($this->settings['limitBacklogToWeeks'])) return true;
		$today = new Week();
		$limit = $today->sub(new \DateInterval("P{$this->settings['limitBacklogToWeeks']}W"));
		return $week->getDateTime() > $limit;
	}

	public function showNextDay($day) {
		if (empty($this->settings['limitBookingToWeeks'])) return true;
		$today = new Week();
		$nextDay = $day->modify('next day');
		$nextDaysWeek = new Week($nextDay->getTimestamp());
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return !($limit < $nextDaysWeek->getDateTime());
	}

	public function showPreviousDay($day) {
		if (empty($this->settings['limitBacklogToWeeks'])) return true;
		$today = new Week();
		$previousDay = $day->modify('previous day');
		$previousDaysWeek = new Week($previousDay->getTimestamp());
		$limit = $today->sub(new \DateInterval("P{$this->settings['limitBacklogToWeeks']}W"));
		return !($limit > $previousDaysWeek->getDateTime());
	}

	public function isAdmin($user = null) {
		if (!$user) $user = $GLOBALS['TSFE']->fe_user;

		return isset($this->settings['admins']) && isset($user->user['uid'])
			? in_array($user->user['uid'], explode(',', $this->settings['admins']))
			: false;
	}

	public function exceededBookingLimit($timestamp) {
		if (empty($this->settings['limitBookingToWeeks'])) return true;
		$today = new Week();
		$week = new Week($timestamp);
		$limit = $today->add(new \DateInterval("P{$this->settings['limitBookingToWeeks']}W"));
		return $week->getDateTime() > $limit;
	}

	public function getMaxBookings() {
		return isset($this->settings['maxBookingsPerDay']) ? (int)$this->settings['maxBookingsPerDay'] : null;
	}
}
