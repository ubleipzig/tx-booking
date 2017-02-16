<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;

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
		$today = new Day();

		$rooms = $this->roomRepository->findAllWithOccupationForWeek($week);

		$this->view->assign('Rooms', $rooms);
		$this->view->assign('today', $today);
		if (true) $this->view->assign('nextWeek', $rooms[0]->getWeek()->modify('Monday next week')->getTimestamp());
		if (true) $this->view->assign('previousWeek', $rooms[0]->getWeek()->modify('Monday last week')->getTimestamp());
	}
}
