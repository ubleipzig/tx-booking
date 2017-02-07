<?php
/***************************************************************
 *  Copyright notice
 *
 *  Forked and modified by Fabian Heusel <fheusel@posteo.de>
 *  Modified by Claas Kazzer <kazzer@ub.uni-leipzig.de>
 *
 ***************************************************************/
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2009-2013 Joachim Ruhs (postmaster@joachim-ruhs.de)
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  A copy is found in the textfile GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * class.tx_ubleipzigbooking_eID.php
 *
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 * @author Fabian Heusel <fheusel@posteo.de>
 * @author Claas Kazzer <kazzer@ub.uni-leipzig.de>
 * @author Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 */

if (!class_exists('tslib_pibase')) require_once(PATH_tslib . 'class.tslib_pibase.php');

require_once(t3lib_extMgm::extPath('lang', 'lang.php'));

if (is_dir(PATH_site . 't3lib')) // not TYPO3 6.2
	require_once(t3lib_extMgm::extPath('cms', 'tslib/class.tslib_content.php'));

$_EXTKEY = 'ubleipzigbooking';
unset($_EXTKEY);

/**
 *
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 * @author Fabian Heusel <fheusel@posteo.de>
 * @author Claas Kazzer <kazzer@ub.uni-leipzig.de>
 * @package TYPO3
 * @subpackage tx_ubleipzigbooking
 */
class tx_ubleipzigbooking_eID {
	protected $ref;
	protected $pid;
	protected $conf;

	/**
	 * $closingDayRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\ClosingDay
	 * @inject
	 */
	protected $closingDayRepository;

	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 * @inject
	 */
	protected $objectManager;

	/**
	 * Initializes the class
	 *
	 * @return  void
	 */
	public function __construct() {
		$GLOBALS['LANG'] = t3lib_div::makeInstance('language');
		$GLOBALS['LANG']->init('default');
		$GLOBALS['TSFE'] = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $GLOBALS['TYPO3_CONF_VARS'], 0, 0);
		$GLOBALS['TSFE']->sys_page = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Frontend\\Page\\PageRepository');

		\TYPO3\CMS\Frontend\Utility\EidUtility::initLanguage();
		$GLOBALS['TSFE']->fe_user = \TYPO3\CMS\Frontend\Utility\EidUtility::initFeUser();
		tslib_eidtools::connectDB();
		if (!$this->objectManager) $this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\ExtBase\Object\ObjectManager');
	}

	/**
	 * Main processing function of eID script
	 *
	 * @return  void
	 */
	public function main() {

		// main is called at the end of
		// this script with $SOBE->main();
		// needed for correct encoding...

		header("Content-type:text/html; charset=utf-8");

		// Init language
		// todo: was soll das sein?! wird das objekt nicht zweifelsfrei im constructor erzeugt?
		if ($GLOBALS['LANG'] instanceof language) {
			$this->language = &$GLOBALS['LANG'];
		} else {
			$this->language = t3lib_div::makeInstance('language');
			$this->language->init($GLOBALS['TSFE']->lang);
		}

		// reading data vars for $this->conf

		$arr = t3lib_div::_GP('data');
		$arr = json_decode(urldecode($arr));
		foreach (get_object_vars($arr) as $key => $value) {
			if (is_string($key) && (is_string($value) || is_int($value))) {
				$this->conf[$key] = $value;
			}

			// reading data vars for $this->_GP in alternative mode
			// not used yet

			if ($key == '_GP' && is_object($value)) {
				foreach (get_object_vars($value) as $key => $value) {
					if (is_string($key) && (is_string($value) || is_int($value))) {
						$this->conf['_GP'][$key] = $value;
					}
				}
			}

			if ($key == '_LOCAL_LANG.' && is_object($value)) {
				foreach (get_object_vars($value) as $key => $value) {
					if (is_string($key) && (is_string($value) || is_object($value))) {
						foreach (get_object_vars($value) as $key1 => $value) {
							$lang = $key;
							$this->conf['_LOCAL_LANG.'][$lang][$key1] = $value;
						}
					}
				}
			}

			if (is_array($value)) {
				echo 'array';
				foreach ($value as $key => $val) {
					echo $val;
				}
			}
		}

		// language from $this->conf['lang']
		// if empty fallback to 'default'

		$this->lang = $this->conf['lang'];
		if ($this->lang == '') $this->lang = 'default';

		// locallang.xml parse

		$this->LOCAL_LANG = t3lib_div::readLLfile('EXT:ubleipzigbooking/pi1/locallang.xml', $this->lang);
		array_push($this->LOCAL_LANG, $this->conf['_LOCAL_LANG.']);

		// Get/Post Variables

		$this->_GP = t3lib_div::_GP('tx_ubleipzigbooking_pi1');

		$this->_GP['year'] = (int)$this->conf['_GP']['year'];
		$this->_GP['month'] = (int)$this->conf['_GP']['month'];
		switch ($this->_GP['action']) {
			case 'viewMonth':
				$this->viewMonth();
				break;

			case 'viewWeek':
				$this->viewWeek();
				break;

			case 'viewBookingForm':
				$this->viewBookingForm();
				break;

			case 'bookObject':
				$this->bookObject();
				break;

			case 'delete':
				$this->deleteBooking();
				break;

			default:
				echo 'Bad mode!';
		}
	}

	function getObjects($uid = 0) {
		$GLOBALS['TYPO3_DB']->debugOutput = 1;
		$fields = "*";
		$where_clause = "  pid IN (" . $this->conf['pid_list'] . ") AND deleted='0' AND hidden='0'";
		if ($uid != 0) $where_clause .= " AND uid = " . $uid;
		$this->_GP['orderBy'] = 'sorting';
		$this->_GP['sort'] = '';
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, 'tx_ubleipzigbooking_object a', $where_clause, '', $this->_GP['orderBy'] . ' ' . $this->_GP['sort'] . $limit);
		$data = array();
		$i = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$data['uid'][$i] = $row['uid'];
			$data['name'][$i] = $row['name'];
			$data['hours'][$i] = $row['hours'];
			$i++;
		}

		return $data;
	}

	function getBookingsOfDay($day, $objectUid) {
		$GLOBALS['TYPO3_DB']->debugOutput = 1;
		$fields = "*";
		$where_clause = "  a.pid IN (" . $this->conf['pid_list'] . ") AND a.deleted='0' AND a.hidden='0' AND b.deleted='0' AND b.disable='0'";
		$where_clause .= "  AND objectuid = " . intval($objectUid) . " AND startdate >= " . $day . " AND startdate < " . ($day + 3600 * 24);
		$where_clause .= " AND b.uid = a.feuseruid";
		$this->_GP['orderBy'] = 'sorting';
		$this->_GP['sort'] = '';
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, 'tx_ubleipzigbooking a, fe_users b', $where_clause, '', $this->_GP['orderBy'] . ' ' . $this->_GP['sort']);
        $data = array();
		$i = 0;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$data['uid'][$i] = $row['uid'];
			$data['startdate'][$i] = $row['startdate'];
			$data['memo'][$i] = stripslashes($row['memo']);
			if ($row['name'] != '') $data['feUserName'][$i] = $row['name'];
			else $data['feUserName'][$i] = $row['first_name'] . ' ' . $row['last_name'];
			if (trim($data['feUserName'][$i]) == '') $data['feUserName'][$i] = $row['username'];
			$data['feUserUid'][$i] = $row['feuseruid'];
			$i++;
		}

		return $data;
	}

	function getBookingsCounts() {
		$lengthOfMonth = array(
			1 => 31,
			28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		);

		// leap year calculating....

		if (date("L", mktime(0, 0, 0, 1, 1, $this->_GP['year'])) == 1) {
			$lengthOfMonth[2] = 29;
		}

		$GLOBALS['TYPO3_DB']->debugOutput = 1;
		$fields = "count(*) as counts";
		for ($d = 1; $d <= $lengthOfMonth[$this->_GP['month']]; $d++) {
			$data[$d] = 0;
			$day = strtotime($this->_GP['year'] . '-' . $this->_GP['month'] . '-' . $d);
			$where_clause = "  a.pid IN (" . $this->conf['pid_list'] . ") AND a.deleted='0' AND a.hidden='0'";
			$where_clause .= "  AND objectuid = " . $this->conf['objectUid'] . " AND startdate >= " . $day . " AND startdate <= " . ($day + 3600 * 23);
			$this->_GP['orderBy'] = '';
			$this->_GP['sort'] = '';
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, 'tx_ubleipzigbooking a', $where_clause, '');
			while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
				$data[$d] = $row['counts'];
			}
		}

		return $data;
	}

	function viewMonth() {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->start('', '');

		// the template file

		$this->template = @file_get_contents(PATH_site . $this->getRelativeFileName($this->conf['templateFile']));
		$template = $this->cObj->getSubpart($this->template, '###MONTHVIEW###');
		$lengthOfMonth = array(
			1 => 31,
			28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		);

		// leap year calculating....

		if (date("L", mktime(0, 0, 0, 1, 1, $this->_GP['year'])) == 1) {
			$lengthOfMonth[2] = 29;
		}

		$objects = $this->getObjects();
		$legend = '<div class="legend">';
		$legend .= '<div class="available"></div><div class="legendTitle">' . $this->getLL('available') . '</div>';
		$legend .= '<div class="someAvailable"></div><div class="legendTitle">' . $this->getLL('someAvailable') . '</div>';
		$legend .= '<div class="notAvailable"></div><div class="legendTitle">' . $this->getLL('notAvailable') . '</div>';
		if ($this->conf['offDutyTimeBegin']) $legend .= '<div class="offDutyTime"></div><div class="legendTitle">' . $this->getLL('offDutyTime') . '</div>';
		$legend .= '</div><div class="clearer"></div>';
		echo $legend;
		$lengthOfMonth = array(
			0,
			31,
			28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		);
		$leapYear = date('L', mktime(0, 0, 0, 1, 1, $this->_GP['year']));
		if ($leapYear) $lengthOfMonth[2] = 29;
		for ($obj = 0; $obj < count($objects['uid']); $obj++) {
			$this->conf['objectUid'] = $objects['uid'][$obj];
			$marks['###OBJECTNAME###'] = $objects['name'][$obj];
			$this->conf['_GP']['month'] = $this->_GP['month'] - 1;
			$this->conf['_GP']['year'] = $this->_GP['year'];
			if ($this->_GP['month'] == 1) {
				$this->conf['_GP']['month'] = 12;
				$this->conf['_GP']['year'] = $this->conf['_GP']['year'] - 1;
			}

			// fetch the bookings for the object

			$counts = $this->getBookingsCounts();
			$ajaxData = urlencode(json_encode($this->conf));
			$marks['###PREVMONTH###'] = '<img src="typo3conf/ext/ubleipzigbooking/pi1/res/arrowleft.png"' . ' onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['action'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['action'] . '\', \'' . $v . '\', \'' . $this->_GP[sort] . '\', \'' . $this->_GP['page'] . '\');"/>';
			$marks['###MONTH###'] = $this->getLL(date("M", strtotime((int)$this->_GP['year'] . "-" . $this->_GP['month'] . "-01"))) . ' ' . (int)$this->_GP['year'];
			$this->conf['_GP']['month'] = $this->_GP['month'] + 1;
			$this->conf['_GP']['year'] = $this->_GP['year'];
			if ($this->conf['_GP']['month'] > 12) {
				$this->conf['_GP']['month'] = 1;
				$this->conf['_GP']['year'] = $this->conf['_GP']['year'] + 1;
			}

			$ajaxData = urlencode(json_encode($this->conf));
			$marks['###NEXTMONTH###'] = '<img src="typo3conf/ext/ubleipzigbooking/pi1/res/arrowright.png"' . ' onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['action'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['action'] . '\', \'' . $v . '\', \'' . $this->_GP[sort] . '\', \'' . $this->_GP['page'] . '\');"/>';
			if (!$this->conf['showDaysShortcuts'] == 1) {

				// display the daynames

				$this->conf['startOfWeek'] = 'monday';
				$out = '<tr><td class="week-no">' . $this->getLL('WeekNo') . '</td>';
				$out .= ($this->conf['startOfWeek'] == 'sunday') ? '<td class="dayNames">' . $this->getLL('Sun') . '</td>' : '';
				$out .= '<td class="dayNames">' . $this->getLL('Mon') . '</td><td class="dayNames">' . $this->getLL('Tue') . '</td><td class="dayNames">' . $this->getLL('Wed') . '</td><td class="dayNames">' . $this->getLL('Thu') . '</td><td class="dayNames">' . $this->getLL('Fri') . '</td><td class="dayNames">' . $this->getLL('Sat');
				$out .= ($this->conf['startOfWeek'] == 'monday') ? '</td><td class="dayNames">' . $this->getLL('Sun') . '</td></tr>' : '</td></tr>';
			}

			$out .= '<tr>';

			// the week function

			$d = 1;
			$onClickWeek = 'tx_ubleipzigbooking_viewWeek(' . $this->_GP['year'] . ',' . $this->_GP['month'] . ',' . $d . ',' . $objects['uid'][$obj] . ',\'' . $ajaxData . '\');';
			$week = date('W', strtotime('0:0:0 ' . 1 . '.' . $this->_GP['month'] . '.' . $this->_GP['year']));
			$out .= '<td class="week" onclick="' . $onClickWeek . '">' . $week . '</td>';

			// calculating the left spaces to get the layout right

			$wd = date('w', strtotime($this->_GP['year'] . "-" . $this->_GP['month'] . "-" . "1"));
			$wd = ($wd == 0) ? 7 : $wd;
			if ($wd != 1) {
				for ($s = 1; $s < $wd; $s++) {
					$out .= '<td class="noDay"> </td>';
				}
			}

			for ($d = 1; $d <= $lengthOfMonth[$this->_GP['month']]; $d++) {
				if (date('d.M.Y', time()) == date('d.M.Y', strtotime('0:0:0 ' . $d . '.' . $this->_GP['month'] . '.' . $this->_GP['year']))) $classToday = 'today';
				else $classToday = '';
				$theDate = strtotime('0:0:0 ' . $d . '.' . $this->_GP['month'] . '.' . $this->_GP['year']);
				$this->conf['date'] = $d . '.' . $this->_GP['month'] . '.' . $this->_GP['year'];
				$ajaxData = urlencode(json_encode($this->conf));
				if (!$this->conf['enableQuarterHourBooking']) {
					if ($this->conf['offDutyTimeBegin'] && $theDate >= $this->conf['offDutyTimeBegin'] && $theDate <= $this->conf['offDutyTimeEnd']) $classToday .= ' offDutyTime';
					if ($counts[$d] < count(explode(',', $objects['hours'][$obj]))) {
						if ($counts[$d] > 0) $out .= '<td class="someAvailable ' . $classToday . '" title="' . $this->getLL('someAvailable') . '" alt="' . $this->getLL('someAvailable') . '"';
						else
							if ($counts[$d] == 0) {
								if ($this->conf['offDutyTimeBegin'] && $theDate >= $this->conf['offDutyTimeBegin'] && $theDate <= $this->conf['offDutyTimeEnd']) {
									$out .= '<td class="available ' . $classToday . '" title="' . $this->getLL('offDutyTime') . '" alt="' . $this->getLL('offDutyTime') . '"';
								} else $out .= '<td class="available ' . $classToday . '" title="' . $this->getLL('available') . '" alt="' . $this->getLL('available') . '"';
							}
					} else {
						$out .= '<td class="notAvailable ' . $classToday . '"';
					}
				}

				if ($this->conf['enableQuarterHourBooking']) {
					if ($counts[$d] < 4 * count(explode(',', $objects['hours'][$obj]))) {
						if ($counts[$d] > 0) $out .= '<td class="someAvailable ' . $classToday . '" title="' . $this->getLL('someAvailable') . '" alt="' . $this->getLL('someAvailable') . '"';
						else
							if ($counts[$d] == 0) $out .= '<td class="available ' . $classToday . '" title="' . $this->getLL('available') . '" alt="' . $this->getLL('available') . '"';
					} else {
						$out .= '<td class="notAvailable ' . $classToday . '"';
					}
				}

				if ($this->conf['offDutyTimeBegin'] && $theDate >= $this->conf['offDutyTimeBegin'] && $theDate <= $this->conf['offDutyTimeEnd']) {
					$out .= ' >';
				} else {
					$out .= ' onclick="tx_ubleipzigbooking_viewBookingForm(' . $this->_GP['year'] . ',' . $this->_GP['month'] . ',' . $d . ',' . $objects['uid'][$obj] . ',\'' . $ajaxData . '\');" >';
				}

				$out .= $d . '</td>';
				$wd = date('w', strtotime($this->_GP['year'] . "-" . $this->_GP['month'] . "-" . $d));
				if ($wd == 0) {
					$week = date('W', strtotime('0:0:0 ' . ($d + 1) . '.' . $this->_GP['month'] . '.' . $this->_GP['year']));
					$onClickWeek = 'tx_ubleipzigbooking_viewWeek(' . $this->_GP['year'] . ',' . $this->_GP['month'] . ',' . ($d) . ',' . $objects['uid'][$obj] . ',\'' . $ajaxData . '\');';
					if ($d == $lengthOfMonth[$this->_GP['month']]) {
						$out .= '</tr>';
					} else {
						$out .= '</tr><tr><td class="week" onclick="' . $onClickWeek . '">' . $week . '</td>';
					}
				}
			}

			$out .= '</tr>';
			$marks['###CAL###'] = $out;
			echo $this->cObj->substituteMarkerArray($template, $marks);
		}
	}

	function viewWeek() {
		list($this->_GP['day'], $this->_GP['objectUid']) = explode(':', $this->_GP['day']);
		$date = new \DateTime($this->_GP['day']);

		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->start('', '');

		$closingDayRepository = $this->objectManager->get('\LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\ClosingDay');
		// the template file

		//$closingDays = $closingDayRepository->findByWeek($date);
		$closingDays = $closingDayRepository->findAll($date);
		$this->template = @file_get_contents(PATH_site . $this->getRelativeFileName($this->conf['templateFile']));
		$this->_GP['objectUid'] = (int)$this->_GP['objectUid'];
		$data = $this->getObjects();
		for ($o = 0; $o < count($data['uid']); $o++) {
			list($year, $month, $day) = explode('-', $this->_GP['day']);
			//if ($day == 1) $day = 0;
			$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
			$lengthOfMonth = array(
				0,
				31,
				28,
				31,
				30,
				31,
				30,
				31,
				31,
				30,
				31,
				30,
				31
			);
			$leapYear = date('L', mktime(0, 0, 0, 1, 1, $this->_GP['year']));
			$week = date('W', mktime(0, 0, 0, $month, $day + 1, $year));
			if ($leapYear) $lengthOfMonth[2] = 29;
			if ($wd > 1 || $wd == 7) {

				// we have to show some days of month before

				$month--;
				if ($month == 0) $month = 12;
				$day = $lengthOfMonth[$month] - $wd + 1;
			}

			for ($i = 0; $i < 7; $i++) {
				if ($day == $lengthOfMonth[intval($month)]) {
					$day = 0;
					$month++;
					if ($month == 13) {
						$month = 1;
						$year++;
					}
				}

				$day++;
				$now = date('d.m.Y', time());

				list($dayNow, $monthNow, $yearNow) = explode('.', $now);
				$this->conf['objectUid'] = $data['uid'][$o];
				$this->conf['date'] = $day . '.' . $month . '.' . $year;
				$ajaxData = urlencode(json_encode($this->conf));
				$marks = array();
				$marks['###TODAY###'] = '';

				if (mktime(0, 0, 0, $month, $day, $year) - time() < 0) $marks['###TODAY###'] = 'pastDay';
				if ($day == $dayNow && $month == $monthNow && $year == $yearNow) $marks['###TODAY###'] = 'today';
				$marks['###OBJECTNAME###'] = $data['name'][$o];
				$marks['###DAY###'] = $day . '.' . $month . '.';
				$hours = explode(',', $data['hours'][$o]);

				// now we fetch the booking data of the objects in this week

				$booking = $this->getBookingsOfDay(mktime(0, 0, 0, $month, $day, $year), $data['uid'][$o]);
				$marks['###WEEKDAY###'] = $this->getLL(date('D', mktime(0, 0, 0, $month, $day, $year)) . '2');
				$bookingHours = array();

				$dateFormat = $this->conf['enableQuarterHourBooking'] ? 'H:i' : 'H';

				// todo: try not to use it
				for ($h = 0; $h < count($booking['startdate']); $h++) {
					$bookingHours[] = date($dateFormat, $booking['startdate'][$h]);
				}

				for ($h = 0; $h < 24; $h++) {
					$startDate = mktime($h, 0, 0, $month, $day, $year);
					$title = '';
					$class = (array_search($h, $hours) !== false) ? $class = 'dutyHours' : 'offDuty';

					if (!$this->conf['enableQuarterHourBooking']) {
						$bookings = array_keys($booking['startdate'], $startDate);
						$ownBookings = array_keys($booking['feUserUid'], $GLOBALS['TSFE']->fe_user->user['uid']);

						if (count($bookings) > 0 && count(array_intersect($bookings, $ownBookings)) > 0) {
							$class = 'ownbookedHours';
						} else if (count($bookings) > 0) {
							$class = 'bookedHours';
						}
					} else {
						$quarter = 0;
						$names = '';
						for ($q = 0; $q < 4; $q++) {
							if ($q == 0) $min = '00';
							else $min = $q * 15;
							if (array_search($h < 10 ? '0' . $h . ':' . $min : $h . ':' . $min, $bookingHours) !== false) {
								$quarter++;
								if ($quarter == 4) $class = 'bookedHours';
								else $class = 'someQuarterAvailable';
								for ($bh = 0; $bh < count($bookingHours); $bh++) {
									if ($bookingHours[$bh] == ($h < 10 ? '0' . $h . ':' . $min : $h . ':' . $min)) $names .= $this->getLL('bookedHour') . '&#x0D;';
								}
							}
						}

						$title = ' alt="' . $names . '" title="' . $names . '"';
					}

					$onclick = in_array($class, ['dutyHours', 'ownbookedHours', 'someQuarterAvailable']) ? 'onclick="tx_ubleipzigbooking_viewBookingForm(' . $this->_GP['year'] . ',' . $month . ',' . $day . ',' . $data['uid'][$o] . ',\'' . $ajaxData . '\');"' : '';

					// offDutyTime

					$theDate = mktime(0, 0, 0, $month, $day, $year);
					if ($this->conf['offDutyTimeBegin'] && $theDate >= $this->conf['offDutyTimeBegin'] && $theDate <= $this->conf['offDutyTimeEnd']) {
						$class .= ' offDutyTimeHours';
						$onclick = '';
					}

					$mark = ($h < 12) ? '###AM###' : '###PM###';

					$marks[$mark] .= '<div class="' . $class . '"' . $title . ' ' . $onclick . '>' . $h . ' &ndash; ' . ($h + 1) . '</div>';
				}

				$template = $this->cObj->getSubpart($this->template, '###WEEKVIEWDAYDATA###');
				$weekdays .= $this->cObj->substituteMarkerArray($template, $marks);
			} // weekday loop
			$template = $this->cObj->getSubpart($this->template, '###WEEKVIEWHEADER###');
			list($pDay, $pMonth, $pYear) = explode('.', date('d.m.Y', mktime(0, 0, 0, $month, ($day - 14), $year)));
			list($nDay, $nMonth, $nYear) = explode('.', date('d.m.Y', mktime(0, 0, 0, $month, $day, $year)));
			$timeDiff = mktime(0, 0, 0, $month, ($day), $year) - time();
			$marks['###PREVIOUSWEEK###'] = 'tx_ubleipzigbooking_viewWeek(' . $pYear . ',' . $pMonth . ',' . $pDay . ',' . $data['uid'][$o] . ',\'' . $ajaxData . '\');';
			if ($this->conf['limitPreviewToDays'] && $timeDiff > $this->conf['limitPreviewToDays'] * 86400) {
				$marks['###NEXTWEEK###'] = '';
				$marks['###HIDDENNEXTWEEK###'] = 'none;';
			} else {
				$marks['###NEXTWEEK###'] = 'tx_ubleipzigbooking_viewWeek(' . $nYear . ',' . $nMonth . ',' . $nDay . ',' . $data['uid'][$o] . ',\'' . $ajaxData . '\');';
				$marks['###HIDDENNEXTWEEK###'] = 'inline;';
			}

			$marks['###CALENDARWEEK###'] = $this->getLL('calendarWeek');
			$marks['###WEEK###'] = $week;
			$marks['###YEAR###'] = $year;
			$marks['###OFFDUTY###'] = $this->getLL('offDutyHour');
			$marks['###BOOKED###'] = $this->getLL('bookedHour');
			$marks['###OWNBOOKED###'] = $this->getLL('ownBookedHour');
			$marks['###AVAILABLE###'] = $this->getLL('availableHour');
			if ($o == 0 && $this->conf['offDutyTimeBegin']) {
				$marks['###OFFDUTYTIME###'] = $this->getLL('offDutyTime');
			} else $marks['###OFFDUTYTIME###'] = '';
			echo $this->cObj->substituteMarkerArray($template, $marks);
			$marks['###WEEKDAYS###'] = $weekdays;
			$template = $this->cObj->getSubpart($this->template, '###WEEKVIEWDATA###');
			echo $this->cObj->substituteMarkerArray($template, $marks);
			$weekdays = '';
		} // object loop
	}

	function viewBookingForm($message = '') {
		$this->cObj = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('tslib_cObj');
		$this->cObj->start('', '');

		// the template file

		$this->template = @file_get_contents(PATH_site . $this->getRelativeFileName($this->conf['templateFile']));
		$template = $this->cObj->getSubpart($this->template, "###BOOKINGFORMHEADER###");
		$marks = $this->getBackToWeekViewMarker();
		if ($message) {
			$marks['###MESSAGE###'] = $message;
			$marks['###MESSAGE###'] .= '<script type="text/javascript">
			$("#messageBoxOverlay").css("display","block")
		   	$(document).ready(function() {
    			$("#messageBox").center();
			});

			$("#messageBox").css("display","block");
			</script>';
			$marks['###CLOSE###'] = $this->getLL('close');
		}

		list($this->_GP['day'], $this->_GP['objectUid']) = explode(':', $this->_GP['day']);
		$this->_GP['objectUid'] = (int)$this->_GP['objectUid'];
		$daytime = strtotime($this->_GP['day']);
		$date = date('d.m.Y', $daytime);
		list($day, $month, $year) = explode('.', $date);
		$marks['###WEEKDAY###'] = $this->getLL(date('l', mktime(0, 0, 0, $month, $day, $year)));
		if ($daytime < strtotime(date('d.m.Y', time()))) $marks['###TODAY###'] = 'pastDay';
		else $marks['###TODAY###'] = '';
		$daytime += 86400;
		$date = date('d.m.Y', $daytime);
		list($day, $month, $year) = explode('.', $date);
		$theDate = $this->conf['date'];
		$this->conf['date'] = $date;
		$ajaxData = urlencode(json_encode($this->conf));
		$onclick = 'tx_ubleipzigbooking_viewBookingForm(' . $year . ',' . $month . ',' . $day . ',' . $this->_GP['objectUid'] . ',\'' . $ajaxData . '\');';
		if (!$this->conf['limitPreviewToDays'] || $this->conf['limitPreviewToDays'] * 86400 + time() > $daytime) $marks['###HIDDENNEXTDAY###'] = 'inline';
		else $marks['###HIDDENNEXTDAY###'] = 'none';
		$marks['###NEXTDAY###'] = $onclick;
		$day = strtotime($this->_GP['day']);
		$day -= 86400;
		$date = date('d.m.Y', $day);
		list($day, $month, $year) = explode('.', $date);
		$this->conf['date'] = $date;
		$ajaxData = urlencode(json_encode($this->conf));
		$onclick = 'tx_ubleipzigbooking_viewBookingForm(' . $year . ',' . $month . ',' . $day . ',' . $this->_GP['objectUid'] . ',\'' . $ajaxData . '\');';
		$marks['###PREVIOUSDAY###'] = $onclick;
		$this->conf['date'] = $theDate;

		// get object name

		$data = $this->getObjects($this->_GP['objectUid']);
		$marks['###OBJECTNAME###'] = $data['name'][0];
		$marks['###DATE###'] = $this->conf['date'];
		$marks['###TIME###'] = $this->getLL('time');
		$marks['###FEUSERNAME###'] = $this->getLL('feUserName');
		$marks['###MEMO###'] = $this->getLL('memo');
		$marks['###BOOK###'] = $this->getLL('book');
		$marks['###DELETE###'] = $this->getLL('delete');
		$hours = $data['hours'][0];
		if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormHeaderHook'])) {
			foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormHeaderHook'] as $_classRef) {
				$_procObj = &t3lib_div::getUserObj($_classRef);
				$_procObj->bookingFormHeaderHook($marks, $row, $this->conf, $this);
			}
		}

		$out = $this->cObj->substituteMarkerArray($template, $marks);

		// get data of the day

		$day = strtotime($this->_GP['day']);
		$template = $this->cObj->getSubpart($this->template, "###BOOKINGFORMDATA###");
		$data = $this->getBookingsOfDay($day, $this->_GP['objectUid']);
		$hours = explode(',', $hours);
		if (!$this->conf['enableQuarterHourBooking']) {
			for ($i = 0; $i < count($hours); $i++) {
				$occupied = 0;
				$hours[$i] = (int)$hours[$i];
				$startdate = strtotime($hours[$i] . ':0:0 ');
				$marks['###TIMEV###'] = $hours[$i] . ':00&ndash;' . ($hours[$i] + 1) . ':00';
				$marks['###FEUSERNAMEV###'] = '';
				$marks['###MEMOV###'] = '';
				$marks['###DELETEV###'] = '';
				$marks['###STATE###'] = '';
				if ($day + $hours[$i] * 3600 > time()) {
					$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . $i . '" class="memo"';
					$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';
				} else {
					$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . $i . '" class="memo" disabled="disabled"';
					$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';
				}

				for ($j = 0; $j < count($data['feUserName']); $j++) {
					if ($data['startdate'][$j] == $day + $hours[$i] * 3600) {
						$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . $i . '" class="memo" disabled="disabled"';
						$marks['###MEMOV###'] .= ' value=""/>';
						$occupied = 1;
						$marks['###STATE###'] .= ' booked';

						if ($this->conf['feUserUid'] == $data['feUserUid'][$j] && $day + $hours[$i] * 3600 > time()) {
							$marks['###STATE###'] .= ' ownbooked';
							$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . $i . '" class="memo" disabled="disabled"';
							$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';
							$this->conf['startdate'] = $data['startdate'][$j];
							$ajaxData = urlencode(json_encode($this->conf));
							$startdate = $data['startdate'][$j];
							$marks['###DELETEV###'] = '<div onclick="tx_ubleipzigbooking_delete(' . $i . ',' . $startdate . ',' . (string)$this->conf['feUserUid'] . ',' . $i . ',\'' . $ajaxData . '\', \'delete\');">';
							$marks['###DELETEV###'] .= '<i class="fa fa-times" aria-hidden="true"></i></div>';
							$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . $i . '" class="memo" disabled="disabled"';
							$marks['###MEMOV###'] .= ' value="' . $data['memo'][$j] . '"/>';
						}
					}
				}

				if (!$occupied && $day + $hours[$i] * 3600 > time() && $GLOBALS['TSFE']->fe_user->user['uid']) {
					$startdate = $day + $hours[$i] * 3600;
					$this->conf['startdate'] = (string)$startdate;
					$ajaxData = urlencode(json_encode($this->conf));
					$marks['###BOOKV###'] = '<div onclick="tx_ubleipzigbooking_book(' . $this->conf['objectUid'] . ',' . $startdate . ',' . (string)$this->conf['feUserUid'] . ',' . $i . ',\'' . $ajaxData . '\');">';
					$marks['###BOOKV###'] .= '<i class="fa fa-check" aria-hidden="true"></i></div>';
				} else $marks['###BOOKV###'] = '';
				if ($this->conf['offDutyTimeBegin'] && $day >= $this->conf['offDutyTimeBegin'] && $day <= $this->conf['offDutyTimeEnd']) $marks['###BOOKV###'] = '';
				if ($this->conf['limitBookingToDays'] && $day > (time() + $this->conf['limitBookingToDays'] * 86400)) $marks['###BOOKV###'] = '';
				if (is_array($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormDataHook'])) {
					foreach ($GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['ubleipzigbooking']['ubleipzigbookingFormDataHook'] as $_classRef) {
						$_procObj = &t3lib_div::getUserObj($_classRef);
						$_procObj->bookingFormDataHook($marks, $row, $this->conf, $this, $hours[$i]);
					}
				}

				$out .= $this->cObj->substituteMarkerArray($template, $marks);
			}
		}

		if ($this->conf['enableQuarterHourBooking']) {
			for ($i = 0; $i < count($hours); $i++) {
				for ($q = 0; $q < 4; $q++) {
					$occupied = 0;
					$hours[$i] = (int)$hours[$i];
					$startdate = strtotime($hours[$i] . ':0:0 ');
					$marks['###TIMEV###'] = $hours[$i] . ':' . $q * 15 . '&ndash;' . $hours[$i] . ':' . ($q + 1) * 15;
					if ($q == 0) $marks['###TIMEV###'] = $hours[$i] . ':00' . '&ndash;' . $hours[$i] . ':' . ($q + 1) * 15;
					if ($q == 3) $marks['###TIMEV###'] = $hours[$i] . ':' . $q * 15 . '&ndash;' . ($hours[$i] + 1) . ':00';
					$marks['###FEUSERNAMEV###'] = '';
					$marks['###MEMOV###'] = '';
					$marks['###DELETEV###'] = '';
					if ($day + $hours[$i] * 3600 + $q * 15 * 60 > time()) {
						$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . ($i * 4 + $q) . '" class="memo"';
						$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';
					} else {
						$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . ($i * 4 + $q) . '" class="memo" disabled="disabled"';
						$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';
					}

					for ($j = 0; $j < count($data['feUserName']); $j++) {
						if ($data['startdate'][$j] == $day + $hours[$i] * 3600 + $q * 15 * 60) {
							$marks['###FEUSERNAMEV###'] = $data['feUserName'][$j];
							$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . ($i * 4 + $q) . '" class="memo" disabled="disabled"';
							$marks['###MEMOV###'] .= ' value="' . htmlspecialchars($data['memo'][$j]) . '"/>';

							if ($this->conf['feUserUid'] == $data['feUserUid'][$j] && $day + $hours[$i] * 3600 + $q * 15 * 60 > time()) {
								$this->conf['startdate'] = $data['startdate'][$j];
								$ajaxData = urlencode(json_encode($this->conf));
								$startdate = $data['startdate'][$j];
								$marks['###DELETEV###'] = '<div onclick="tx_ubleipzigbooking_delete(' . $i . ',' . $startdate . ',' . (string)$this->conf['feUserUid'] . ',' . $i . ',\'' . $ajaxData . '\', \'delete\');">';
								$marks['###DELETEV###'] .= '<i class="fa fa-times" aria-hidden="true"></i></div>';
								$marks['###MEMOV###'] = '<input type="text" id="tx_ubleipzigbooking_pi1_memo' . ($i * 4 + $q) . '" class="memo" disabled="disabled"';
								$marks['###MEMOV###'] .= ' value="' . $data['memo'][$j] . '"/>';
							}
						}
					}

					if (!$occupied && $day + $hours[$i] * 3600 + $q * 15 * 60 > time() && $GLOBALS['TSFE']->fe_user->user['uid']) {
						$startdate = $day + $hours[$i] * 3600 + $q * 15 * 60;
						$this->conf['startdate'] = (string)$startdate;
						$ajaxData = urlencode(json_encode($this->conf));
						$marks['###BOOKV###'] = '<div onclick="tx_ubleipzigbooking_book(' . $this->conf['objectUid'] . ',' . $startdate . ',' . (string)$this->conf['feUserUid'] . ',' . ($i * 4 + $q) . ',\'' . $ajaxData . '\');">';
						$marks['###BOOKV###'] .= '<i class="fa fa-check" aria-hidden="true"></i></div>';
					} else $marks['###BOOKV###'] = '';
					$out .= $this->cObj->substituteMarkerArray($template, $marks);
				}
			}
		}

		$template = $this->cObj->getSubpart($this->template, "###BOOKINGFORMFOOTER###");
		$marks = $this->getBackToWeekViewMarker();
		$out .= $this->cObj->substituteMarkerArray($template, $marks);
		echo $out;
	}

	function getBackToWeekViewMarker() {
		list($day, $month, $year) = explode('.', $this->conf['date']);
		$marks['###BACKTOWEEKVIEWTITLE###'] = $this->getLL('backToWeekView');
		$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
		$lengthOfMonth = array(
			0,
			31,
			28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		);
		$leapYear = date('L', mktime(0, 0, 0, 1, 1, $this->_GP['year']));
		$date = date('d.m.Y', mktime(0, 0, 0, $month, ($day - $wd), $year));
		list($day, $month, $year) = explode('.', $date);
		$this->conf['action'] = 'viewWeek';
		$ajaxData = urlencode(json_encode($this->conf));
		$marks['###BACKTOWEEKVIEW###'] = 'tx_ubleipzigbooking_viewWeek(' . $year . ',' . $month . ',' . $day . ',' . 1 . ',\'' . $ajaxData . '\');';
		$marks['###BACKTOMONTHVIEWTITLE###'] = $this->getLL('backToMonthView');
		$wd = date('w', mktime(0, 0, 0, $month, $day, $year));
		$lengthOfMonth = array(
			0,
			31,
			28,
			31,
			30,
			31,
			30,
			31,
			31,
			30,
			31,
			30,
			31
		);
		$leapYear = date('L', mktime(0, 0, 0, 1, 1, $this->_GP['year']));
		$date = date('d.m.Y', mktime(0, 0, 0, $month, ($day - $wd), $year));
		list($day, $month, $year) = explode('.', $date);
		$this->conf['action'] = 'viewMonth';
		$this->conf['_GP']['month'] = $month;
		$this->conf['_GP']['year'] = $year;
		$ajaxData = urlencode(json_encode($this->conf));
		$marks['###BACKTOMONTHVIEW###'] = 'tx_ubleipzigbooking_submit(\'' . $this->conf['action'] . '\',\'' . $ajaxData . '\', \'viewMonth\', \'feusername\', \'desc\');';
		if ($this->conf['enableQuarterHourBooking']) {
			$marks['###BACKTOMONTHVIEWTITLE###'] = '';
			$marks['###BACKTOMONTHVIEW###'] = '';
		}

		return $marks;
	}

	function bookObject() {
		$GLOBALS['TYPO3_DB']->debugOutput = false;

		list($day, $month, $year) = explode('.', $this->conf['date']);
		$this->_GP['day'] = $year . '-' . $month . '-' . $day . ':' . $this->conf['objectUid'];

		$fields = 'count(*) as count';
		$date = date('d.m.Y', $this->conf['startdate']);
		$dayTime = strtotime($date);
		$where = ' feUserUid = ' . $GLOBALS['TSFE']->fe_user->user['uid'] . ' AND startdate > ' . $dayTime . ' AND startdate < ' . ($dayTime + 86400) . ' AND deleted = 0';
		$bookings = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow($fields, 'tx_ubleipzigbooking', $where, '', '');

		$fields = array(
			'pid' => $this->conf['pid_list'],
			'objectUid' => $this->conf['objectUid'],
			'feuseruid' => $this->conf['feUserUid'],
			'startdate' => $this->conf['startdate'],
			'memo' => strip_tags($this->_GP['memo']),
			'cruser_id' => $this->conf['feUserUid'],
			'crdate' => time(),
			'tstamp' => time(),
		);

		// check maxBookingsPerDay, inform the user
		if ($this->conf['maxBookingsPerDay'] && $bookings['count'] >= $this->conf['maxBookingsPerDay']) {
			return $this->viewBookingForm($this->getLL('maxBookingsPerDay'));
		}
		// booking in past, pretend nothing happened
		// todo: why do we need $this->conf['feUserUid'] ?
		if ($this->conf['startdate'] < time() || $this->conf['feUserUid'] != $GLOBALS['TSFE']->fe_user->user['uid']) {
			return $this->viewBookingForm();
		}
		// try to insert
		if ($GLOBALS['TYPO3_DB']->exec_INSERTquery('tx_ubleipzigbooking', $fields)) {
			// send mail if required
			if ($this->conf['enableFeUserMail']) $this->sendFeUserMail($this->conf['feUserUid'], $this->conf['objectUid'], $this->conf['startdate']);
			return $this->viewBookingForm();
			// an sql error occured
		} else {
			// duplicate entry, inform the user
			if (1062 === $GLOBALS['TYPO3_DB']->sql_errno()) {
				return $this->viewBookingForm($this->getLL('alreadyBooked'));
				// unknown error, pretend nothing happened
			} else {
				return $this->viewBookingForm();
			}
		}
	}

	function deleteBooking() {
		$fields = array(
			'pid' => $this->conf['pid_list'],
			'objectUid' => $this->conf['objectUid'],
			'feuseruid' => $this->conf['feUserUid'],
			'startdate' => $this->conf['startdate'],
			'cruser_id' => $this->conf['feUserUid'],
			'crdate' => time(),
			'tstamp' => time(),
		);
		$where = 'objectUid=' . $this->conf['objectUid'];
		$where .= ' AND feuseruid=' . $this->conf['feUserUid'];
		$where .= ' AND startdate=' . $this->conf['startdate'];
		$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_ubleipzigbooking', $where);
		list($day, $month, $year) = explode('.', $this->conf['date']);
		$this->_GP['day'] = $year . '-' . $month . '-' . $day . ':' . $this->conf['objectUid'];
		$this->viewBookingForm();
	}

	function sendFeUserMail($feUserUid, $objectUid, $startDate) {
		$this->cObj = t3lib_div::makeInstance('tslib_cObj');
		$this->cObj->start('', '');

		// the template file

		$this->template = @file_get_contents(PATH_site . $this->getRelativeFileName($this->conf['templateFile']));
		$template = $this->cObj->getSubpart($this->template, '###FEUSERMAIL###');
		$data = $this->getObjects($objectUid);
		$marks['###BOOKINGOBJECTV###'] = $data['name'][0];
		$marks['###BOOKINGDATEV###'] = date('d.m.Y', $startDate);
		$marks['###BOOKINGTIMEV###'] = date('H:00', $startDate);
		$table = 'fe_users';
		$where = 'uid=' . intval($this->conf['feUserUid']);
		$fields = '*';
		$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery($fields, $table, $where);
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
			$data['name'] = $row['name'];
			$data['email'] = $row['email'];
		}

		$marks['###FEUSERNAMEV###'] = $data['name'];
		$out = $this->cObj->substituteMarkerArray($template, $marks);

		// now we have the mail text to be send

		$recipient = $data['email'];
		$subject = $this->conf['mailSubject'];
		$html_start = '<html><head><title>' . $subject . '</title></head><body>';
		$html_end = '</body></html>';
		$mail = t3lib_div::makeInstance('t3lib_mail_message');
		$mail->setFrom(array(
			$this->conf['mailFrom'] => $this->conf['mailFromName']
		));
		$mail->setTo(array(
			$data['email'] => $data['name']
		));
		$mail->setSubject($subject);
		$mail->setBody($html_start . $out . $html_end, 'text/html');

		// Create the attachment
		// * Note that you can technically leave the content-type parameter out
		// (optional) setting the filename

		if ($this->conf['mailAttachment']) {
			$attachment = Swift_Attachment::fromPath(PATH_site . $this->conf['mailAttachment']);

			// Attach it to the message

			$mail->attach($attachment);
		}

		$mail->send();
	}

	function getLL($s) {
		if ($this->conf['_LOCAL_LANG.'][$this->lang . '.'][$s]) return $this->conf['_LOCAL_LANG.'][$this->lang . '.'][$s];
		global $TYPO3_CONF_VARS;
		if ($TYPO3_CONF_VARS['SYS']['compat_version'] < '4.6') return $this->LOCAL_LANG[$this->lang][$s];
		else {
			$_label = $this->LOCAL_LANG[$this->lang][$s];
			if ($_label[0]['target']) return $_label[0]['target'];
			else {
				$_label = $this->LOCAL_LANG['default'][$s];
				return $_label[0]['target'];
			}
		}
	}

	function getRelativeFileName($filename) {
		if (substr($filename, 0, 4) == 'EXT:') {
			list($extKey, $local) = explode('/', substr($filename, 4), 2);
			$filename = '';
			if (strcmp($extKey, '') && t3lib_extMgm::isLoaded($extKey) && strcmp($local, '')) {
				return t3lib_extMgm::siteRelPath($extKey) . $local;
			}
		} else return $filename;
	}

	function getListPageBrowser(&$marks, &$ajaxData) {

		// $nC = number of center pages
		// display like this 1 2 .. 4 5 6 7 8 .. 10 11
		// when actual page is 6

		$nc = 5;
		$p = $this->_GP['page'];
		if ($this->conf['listRecordsPerPage'] > 0) $n = ceil($this->counts / $this->conf['listRecordsPerPage']);
		$marks['###PAGEBROWSER###'] = '';
		if ($n == 1) return;

		// simple page browser

		if ($n <= $nc + 4) {
			for ($i = 1; $i <= $n; $i++) {
				if ($i == $p) $marks['###PAGEBROWSER###'] .= '<span class="act">' . $i . '</span>';
				else $marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['mode'] . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
			}
		}

		// extended page browser

		if ($n > $nc + 4) {
			if ($p <= (int)($nc / 2) + 2) {

				// 1 2 3 4 5 6 7.. 13 14

				for ($i = 1; $i <= $nc + 2; $i++) {
					if ($i == $p) $marks['###PAGEBROWSER###'] .= '<span class="act">' . $i . '</span>';
					else $marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
				}

				$marks['###PAGEBROWSER###'] .= '..';
				for ($i = 1; $i >= 0; $i--) {
					$marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . ($n - $i) . '\');">' . ($n - $i) . '</span>';
				}
			}

			if ($p + nc / 2 <= $n - 4 && $p > $nc / 2 + 2) {

				// 1 2 .. 8 9 10 11 12 .. 15 16

				for ($i = 1; $i <= 2; $i++) {
					$marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
				}

				if ($p - (int)($nc / 2) != 3) $marks['###PAGEBROWSER###'] .= '..';
				for ($i = $p - (int)($nc / 2); $i <= $p + (int)($nc / 2); $i++) {
					if ($i == $p) $marks['###PAGEBROWSER###'] .= '<span class="act">' . $i . '</span>';
					else $marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
				}

				if ($p + (int)($nc / 2) != $n - 2) $marks['###PAGEBROWSER###'] .= '..';
				for ($i = n - 1; $i <= n; $i++) {
					$marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . ($i + $n) . '\');">' . ($i + $n) . '</span>';
				}
			}

			if ($p + nc / 2 >= $n - 3) {

				// 1 2 .. 22 23 24 25 26

				for ($i = 1; $i <= 2; $i++) {
					if ($i == $p) $marks['###PAGEBROWSER###'] .= '<span class="act">' . $i . '</span>';
					else $marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
				}

				$marks['###PAGEBROWSER###'] .= '..';
				for ($i = $n - $nc; $i <= $n; $i++) {
					if ($i == $p) $marks['###PAGEBROWSER###'] .= '<span class="act">' . $i . '</span>';
					else $marks['###PAGEBROWSER###'] .= '<span class="norm" onclick="tx_ubleipzigbooking_submit(\'' . $this->_GP['mode'] . '\',\'' . $ajaxData . '\', \'' . $this->_GP['orderBy'] . '\', \'' . $this->_GP[sort] . '\', \'' . $i . '\');">' . $i . '</span>';
				}
			}
		}
	}
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/class.tx_ubleipzigbooking_eID.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/class.tx_ubleipzigbooking_eID.php']);

}

// Make instance:

$SOBE = t3lib_div::makeInstance('tx_ubleipzigbooking_eID');
$SOBE->main();
