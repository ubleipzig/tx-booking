<?php
/**
 * Class Hour
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
 * Class Hour
 *
 * @package LeipzigUniversityLibrary\UblBooking\Library
 */
class Hour extends DateHelper {

	/**
	 * Hour constructor.
	 *
	 * @param int $timestamp [optional] the unix timestamp to create the object from
	 */
	public function __construct($timestamp = null) {
		parent::__construct($timestamp);

		$this->origin = $this->origin->modify('this hour');
	}
}