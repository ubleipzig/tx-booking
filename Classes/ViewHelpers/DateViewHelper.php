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

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

/**
 * Class DateViewHelper
 *
 * @package Ubl\Booking\ViewHelpers
 */
class DateViewHelper extends AbstractViewHelper
{
    /**
     * Initializes arguments
     *
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('format', 'string','The output format', true);
        $this->registerArgument('object', 'object','Date object', true);
        $this->registerArgument('modify', 'string','Modification of object done before processing', false, null);
    }

	/**
     * Render date
	 *
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     *
     * @return string
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $format = $arguments['format'];
        $object = $arguments['object'];
        $modify = $arguments['modify'] ?? null;

        if ($modify) {
            $object = $object->modify($modify);
        }
        return $object->format($format);
    }
}