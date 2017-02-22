<?php
namespace LeipzigUniversityLibrary\ubleipzigbooking\Controller;

use \LeipzigUniversityLibrary\ubleipzigbooking\Library\Day;

class WeekController extends AbstractController {

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
		$rooms = $this->roomRepository->findAllWithOccupationForWeek($week, $this->settings);


		$this->view->assign('Today', new Day());
		$this->view->assign('Now', new \DateTimeImmutable('now', new \DateTimeZone('Europe/Berlin')));
		$this->view->assign('Rooms', $rooms);
		if (true) $this->view->assign('nextWeek', $rooms[0]->getWeek()->modify('Monday next week')->getTimestamp());
		if (true) $this->view->assign('previousWeek', $rooms[0]->getWeek()->modify('Monday last week')->getTimestamp());
	}
}
