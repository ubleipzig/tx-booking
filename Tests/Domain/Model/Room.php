<?php

use \LeipzigUniversityLibrary\ubleipzigbooking\Domain\Model\Room;

class RoomTest extends \TYPO3\CMS\Core\Tests\BaseTestCase {
	/**
	 * @test
	 */
	public function anInstanceOfTheRoomCanBeConstructed() {
		$room = new Room('Name');
		$this->assertEquals('Name', $room->getName());
	}
}