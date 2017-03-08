<?php
/**
 * Class AbstractEntity
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

namespace LeipzigUniversityLibrary\UblBooking\Library;

/**
 * Class AbstractEntity
 *
 * @package LeipzigUniversityLibrary\UblBooking\Library
 */
abstract class AbstractEntity extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * The plugin settings set by controller
	 *
	 * @var array
	 */
	protected $settingsHelper;

	/**
	 * The magic method covers all setters and getters
	 *
	 * @param string $method
	 * @param array  $arguments
	 * @return mixed
	 * @throws \Exception in case of method or property are invalid
	 */
	public function __call($method, $arguments) {
		$pattern = '/^(?<method>[gs]et)(?<property>.*)$/';

		$matches = [];

		if (!preg_match($pattern, $method, $matches)) {
			throw new \Exception('no handling for method ' . $method . ' defined');
		}

		$property = lcfirst($matches['property']);

		if (!$this->_hasProperty($property)) {
			throw new \Exception('property ' . $property . ' does not exist in ' . get_class($this));
		}

		if ($matches['method'] === 'set') {
			return $this->_setProperty($property, $arguments[0]);
		} else if ($matches['method'] === 'get') {
			return $this->_getProperty($property);
		}
	}
}
