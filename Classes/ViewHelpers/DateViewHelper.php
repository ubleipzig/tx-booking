<?php
/**
 * Class DateViewHelper
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

namespace Ubl\Booking\ViewHelpers;

/**
 * Class DateViewHelper
 *
 * @package Ubl\Booking\ViewHelpers
 */
class DateViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @param string $format the output format
	 * @param object \DateTimeInterface $object the date
	 * @param string $modify [optional] modification to the object made before formatting
	 * @return string
	 */
	public function render($format, $object, $modify = null) {
		if ($modify) {
			$object = $object->modify($modify);
		}
		return $object->format($format);
	}

}