<?php
/**
 * Class Tca
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
 * Class Tca
 *
 * backend helper methods
 *
 * @package Ubl\Booking\Library
 */
class Tca
{
	/**
	 * The opening hours repository
	 *
	 * @var \Ubl\Booking\Domain\Repository\OpeningHours
	 * @inject
	 */
	protected $openingHoursRepository;

	/**
	 * Sets the week days as select items for backend form
	 *
	 * @param $config
     *
	 * @return mixed
	 * @throws \Exception
	 */
	public function getDays($config)
    {
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Object\ObjectManager');
		$querySettings = $objectManager->get('TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings');
		$openingHoursRepository = $objectManager->get('Ubl\Booking\Domain\Repository\OpeningHours');

		// workaround, see https://forge.typo3.org/issues/50551
		$pageUid = $this->normalizePageUid($config['row']['pid']);

		$querySettings->setStoragePageIds([$pageUid]);
		$openingHoursRepository->setDefaultQuerySettings($querySettings);
		$openingHours = [];
		$week = new Week();

		foreach($openingHoursRepository->findAll() as $weekday) {
			$openingHours[] = $weekday->getWeekDay();
		}
		foreach ($week as $key => $day) {
			if (!in_array($key, $openingHours) || (string)$key === $config['row']['week_day']) $config['items'][] = [$day->format('l'),$key];
		}

		if (count($config['items']) === 0) throw new \Exception('no week days left');

		return $config;
	}

	/**
	 * Sets the title parameter for listing in backend view
	 *
	 * @param $parameters
	 * @param $parentObject
	 */
	public function getDayTitle(&$parameters, $parentObject)
    {
		$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
		$week = new Week();
		$i = $record['week_day'] ? (int)$record['week_day'] -1 : 0;
		$hours = explode(',', $record['hours']);
		sort($hours, SORT_NUMERIC);
		$parameters['title'] = $week->add(new \DateInterval("P{$i}D"))->format('l') . ' (' . implode(',', $hours) . ')';
	}

	/**
	 * sets the choosable opening hours as select items in backend form
	 *
	 * @param $config
	 * @return mixed
	 */
	public function getHours($config)
    {
		$day = new Day();
		foreach ($day as $key => $hour) {
			$title = $hour->format('H:i') . ' - ' . $hour->modify('next hour')->format('H:i');
			$config['items'][] = [$title ,$key];
		}
		return $config;
	}

	/**
	 * finds the correct pid after "save+new"
	 *
	 * @param $id
     *
	 * @return mixed
	 */
	protected function normalizePageUid($id)
    {
		if ($id < 0) {
			$parentRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
				'tx_booking_domain_model_openinghours',
				abs($id),
				'pid'
			);
			return $parentRec['pid'];
		} else {
			return $id;
		}
	}
}