<?php

namespace LeipzigUniversityLibrary\Ublbooking\Library;

class Tca {

	/**
	 * @var \LeipzigUniversityLibrary\Ublbooking\Domain\Repository\OpeningHours
	 * @inject
	 */
	protected $openingHoursRepository;

	public function getDays($config) {
		$objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\TYPO3\CMS\Extbase\Object\ObjectManager');
		$querySettings = $objectManager->get('TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$openingHoursRepository = $objectManager->get('\LeipzigUniversityLibrary\Ublbooking\Domain\Repository\OpeningHours');

		// workaround, see https://forge.typo3.org/issues/50551
		$pageUid = $this->normalizePageUid($config['row']['pid']);

		$querySettings->setStoragePageIds(array($pageUid));
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

	public function getDayTitle(&$parameters, $parentObject) {
		$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($parameters['table'], $parameters['row']['uid']);
		$week = new Week();

		$i = $record['week_day'] ? (int)$record['week_day'] -1 : 0;
		$hours = explode(',', $record['hours']);
		sort($hours, SORT_NUMERIC);
		$parameters['title'] = $week->add(new \DateInterval("P{$i}D"))->format('l') . ' (' . implode(',', $hours) . ')';
	}

	public function getHours($config) {
		$day = new Day();

		foreach ($day as $key => $hour) {
			$title = $hour->format('H:i') . ' - ' . $hour->modify('next hour')->format('H:i');
			$config['items'][] = [$title ,$key];
		}
		return $config;
	}

	protected function normalizePageUid($id) {
		if ($id < 0) {
			$parentRec = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord(
				'tx_ublbooking_domain_model_openinghours',
				abs($id),
				'pid'
			);

			return $parentRec['pid'];
		} else {
			return $id;
		}
	}
}