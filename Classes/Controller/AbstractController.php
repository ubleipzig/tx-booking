<?php
/**
 * Class AbstractController
 *
 * Copyright (C) Leipzig University Library 2017 <info@ub.uni-leipzig.de>
 *
 * @author  Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
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

namespace Ubl\Booking\Controller;

use Ubl\Booking\Library\SettingsHelper;

/**
 * Class AbstractController
 *
 * Provides common methods to use in all controllers
 *
 * @package Ubl\Booking\Controller
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController
{
	/**
	 * @var Ubl\Booking\Library\SettingsHelper
	 */
	protected $settingsHelper;

	/**
	 * Override getErrorFlashMessage to present flash error messages for booking.
	 *
	 * @return string
	 */
	protected function getErrorFlashMessage()
    {
		$defaultFlashMessage = parent::getErrorFlashMessage();
		$locallangKey = sprintf('error.%s.%s', $this->request->getControllerName(), $this->actionMethodName);
		return $this->translate($locallangKey, $defaultFlashMessage);
	}

	/**
	 * Helper function to render localized flashmessages
	 *
	 * @param string  $action
	 * @param integer $severity [optional] Severity code. One of the t3lib_FlashMessage constants
     *
	 * @return void
	 */
	public function addFlashMessageHelper($action, $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK)
    {
		$messageLocallangKey = sprintf('flashmessage.%s.%s', $this->request->getControllerName(), $action);
		$localizedMessage = $this->translate($messageLocallangKey, '[' . $messageLocallangKey . ']');
		$titleLocallangKey = sprintf('%s.title', $messageLocallangKey);
		$localizedTitle = $this->translate($titleLocallangKey, '[' . $titleLocallangKey . ']');
		parent::addFlashMessage($localizedMessage, $localizedTitle, $severity);
	}

	/**
	 * Helper function to use localized strings in controllers
	 *
	 * @param string $key            Key $locallang
	 * @param string $defaultMessage Default message to show if key was not found
     *
	 * @return string
	 */
	protected function translate($key, $defaultMessage = '')
    {
		$message = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'booking');
		if ($message === NULL) {
			$message = $defaultMessage;
		}
		return $message;
	}

	/**
	 * Initialization method invoked before action method is invoked
	 *
	 * @return void
	 */
	public function initializeAction()
    {
		$this->settingsHelper = new SettingsHelper($this->settings);
	}
}