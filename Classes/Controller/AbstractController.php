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

namespace LeipzigUniversityLibrary\UblBooking\Controller;

use LeipzigUniversityLibrary\UblBooking\Library\SettingsHelper;

/**
 * Class AbstractController
 *
 * Provides common methods to use in all controllers
 *
 * @package LeipzigUniversityLibrary\UblBooking\Controller
 */
abstract class AbstractController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * @var \LeipzigUniversityLibrary\UblBooking\Library\SetingsHelper
	 */
	protected $settingsHelper;

	/**
	 * Override getErrorFlashMessage to present
	 * nice flash error messages.
	 *
	 * @return string
	 */
	protected function getErrorFlashMessage() {
		$defaultFlashMessage = parent::getErrorFlashMessage();
		$locallangKey = sprintf('error.%s.%s', $this->request->getControllerName(), $this->actionMethodName);
		return $this->translate($locallangKey, $defaultFlashMessage);
	}

	/**
	 * helper function to render localized flashmessages
	 *
	 * @param string  $action
	 * @param integer $severity optional severity code. One of the t3lib_FlashMessage constants
	 * @return void
	 */
	public function addFlashMessage($action, $severity = \TYPO3\CMS\Core\Messaging\FlashMessage::OK) {
		$messageLocallangKey = sprintf('flashmessage.%s.%s', $this->request->getControllerName(), $action);
		$localizedMessage = $this->translate($messageLocallangKey, '[' . $messageLocallangKey . ']');
		$titleLocallangKey = sprintf('%s.title', $messageLocallangKey);
		$localizedTitle = $this->translate($titleLocallangKey, '[' . $titleLocallangKey . ']');
		$this->flashMessageContainer->add($localizedMessage, $localizedTitle, $severity);
	}

	/**
	 * helper function to use localized strings in controllers
	 *
	 * @param string $key            locallang key
	 * @param string $defaultMessage the default message to show if key was not found
	 * @return string
	 */
	protected function translate($key, $defaultMessage = '') {
		$message = \TYPO3\CMS\Extbase\Utility\LocalizationUtility::translate($key, 'ubl_booking');
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
	public function initializeAction() {
		$this->settingsHelper = new SettingsHelper($this->settings);
	}
}