<?php

namespace LeipzigUniversityLibrary\ubleipzigbooking\Library;

abstract class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * plugin settings set by controller
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * magic method covers all setters and getters
	 *
	 * @param string $method
	 * @param array $arguments
	 * @return mixed
	 * @throws \Exception in case of method or property are invalid
	 */
	public function __call($method, $arguments) {
		$pattern = '/^(?<method>[gs]et)(?<property>.*)$/';

		$matches = [];

		if (!preg_match($pattern, $method, $matches)) {
			throw new \Exception('no handling for method '. $method . ' defined');
		}

		$property = lcfirst($matches['property']);

		if (!$this->_hasProperty($property)) {
			throw new \Exception('property '. $property . ' does not exist in ' . get_class($this));
		}

		if ($matches['method'] === 'set') {
			return $this->_setProperty($property, $arguments[0]);
		} else if ($matches['method'] === 'get') {
			return $this->_getProperty($property);
		}
	}
}
