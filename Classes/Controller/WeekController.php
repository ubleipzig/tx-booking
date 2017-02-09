<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Week;
class WeekController extends ActionController {

	/**
	/**
	 * $roomRepository
	 *
	 * @var \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Repository\Room
	 * @inject
	 */
	protected $roomRepository;

	/**
	 * @param integer $week
	 */
	public function showAction($week = null) {
		$week = new Week($week);

//		$rooms = $this->roomRepository->findAllBetween($week->format('Monday day this week'), $week->format('Sunday day this week'));

		$rooms = $this->roomRepository->findAll();
		$this->view->assign('Week', $week);
		$this->view->assign('Rooms', $rooms);

		if (true) $this->view->assign('nextWeek', $week->modify('Monday next week')->getTimestamp());
		if (true) $this->view->assign('previousWeek', $week->modify('Monday last week')->getTimestamp());
	}
}
