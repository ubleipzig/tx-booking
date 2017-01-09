<?php
/***************************************************************
*  Copyright notice
*
*  Forked and modified by Fabian Heusel <fheusel@posteo.de>
*  Modified by Claas Kazzer <kazzer@ub.uni-leipzig.de>
***************************************************************/
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2013 Joachim Ruhs <postmaster@joachim-ruhs.de>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * Plugin 'ubleipzigbooking' for the 'ubleipzigbooking' extension.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 * @author Fabian Heusel <fheusel@posteo.de>
 * @author Claas Kazzer <kazzer@ub.uni-leipzig.de>
 */

if (!class_exists('tslib_pibase')) require_once (PATH_tslib . 'class.tslib_pibase.php');

// require_once(PATH_t3lib.'class.t3lib_div.php');

class tx_ubleipzigbooking_pi1 extends tslib_pibase

{
    var $prefixId = 'tx_ubleipzigbooking_pi1'; // Same as class name
    var $scriptRelPath = 'pi1/class.tx_ubleipzigbooking_pi1.php'; // Path to this script relative to the extension dir.
    var $extKey = 'ubleipzigbooking'; // The extension key.
    var $pi_checkCHash = TRUE;
    var $mode = '';
    /**
     * Main method of your PlugIn
     *
     * @param        string               $content: The content of the PlugIn
     * @param        array                $conf: The PlugIn Configuration
     * @return       The content that should be displayed on the website
     */
    function main($content, $conf)
    {
        $this->pi_setPiVarDefaults();
        $this->pi_loadLL();
        $this->conf = $conf;

        // reading plugin parameters

        $this->readExtConf();
        $pidList = $this->pi_getPidList($this->cObj->data['pages'], $this->cObj->data['recursive']);
        if ($pid_list == '') $pid_list = $GLOBALS->TSFE['pid'];
        $this->conf['pid_list'] = $pidList;

        // Get/Post Variables

        $this->_GP = t3lib_div::_GP('tx_ubleipzigbooking_pi1');
        $this->cObj = t3lib_div::makeInstance("tslib_cObj");
        if ($this->piVars['mode'])
        if (!preg_match('/^[A-z]*$/', $this->piVars['mode'])) die("mode input error");
        switch ($this->conf["displayMode"])
        {
        default:
            $this->_GP['action'] = $this->conf['displayMode'];
            require_once (t3lib_extMgm::extPath('ubleipzigbooking') . "pi1/class.tx_ubleipzigbooking_controller.php");

            $controller = t3lib_div::makeInstance('tx_ubleipzigbooking_controller');
            if (!isset($this->_GP['action'])) $this->_GP['action'] = 'viewMonth';
            $controller->setGPVars($this->_GP);
            $controller->setConfiguration($this->conf);
            $controller->setLocalLang($this->LOCAL_LANG);
            $content = $controller->handle();
            return $this->pi_wrapInBaseClass($content);
            break;
        }
    }

    function readExtConf()
    {

        // get the configuration from typoscript.
        // if there are no typoscript-values get the values from flexform

        $this->pi_initPIflexForm();
        if (empty($this->conf['cssFile'])) $this->conf['cssFile'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'cssFile', 'sheetTemplateOptions');
        if (empty($this->conf['templateFile'])) $this->conf['templateFile'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'templateFile', 'sheetTemplateOptions');
        if (empty($this->conf['displayMode'])) $this->conf['displayMode'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'displayMode', 'sheetDisplayMode');
        if (empty($this->conf['includejQuery'])) $this->conf['includejQuery'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'includejQuery', 'sheetDisplayMode');
        if (!isset($this->conf['enableQuarterHourBooking'])) $this->conf['enableQuarterHourBooking'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'enableQuarterHourBooking', 'sheetDisplayMode');
        if (!isset($this->conf['maxBookingsPerDay'])) $this->conf['maxBookingsPerDay'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'maxBookingsPerDay', 'sheetDisplayMode');
        if (!isset($this->conf['limitPreviewToDays'])) $this->conf['limitPreviewToDays'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'limitPreviewToDays', 'sheetDisplayMode');
        if (!isset($this->conf['limitBookingToDays'])) $this->conf['limitBookingToDays'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'limitBookingToDays', 'sheetDisplayMode');
        if (!isset($this->conf['enableFeUserMail'])) $this->conf['enableFeUserMail'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'enableFeUserMail', 'sheetDisplayMode');
        if (!isset($this->conf['mailSubject'])) $this->conf['mailSubject'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mailSubject', 'sheetDisplayMode');
        if (!isset($this->conf['mailFromName'])) $this->conf['mailFromName'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mailFromName', 'sheetDisplayMode');
        if (!isset($this->conf['mailFrom'])) $this->conf['mailFrom'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mailFrom', 'sheetDisplayMode');
        if (!isset($this->conf['mailAttachment'])) $this->conf['mailAttachment'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'mailAttachment', 'sheetDisplayMode');
        if (!isset($this->conf['offDutyTimeBegin'])) $this->conf['offDutyTimeBegin'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'offDutyTimeBegin', 'sheetDisplayMode');
        if (!isset($this->conf['offDutyTimeEnd'])) $this->conf['offDutyTimeEnd'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'offDutyTimeEnd', 'sheetDisplayMode');
        if (empty($this->conf['showWarnings'])) $this->conf['showWarnings'] = $this->pi_getFFvalue($this->cObj->data['pi_flexform'], 'showWarnings', 'sheetDisplayMode');

        // Get/Post Variables

        $this->_GP = t3lib_div::_GP('tx_ubleipzigbooking_pi1');

        // Template

        $templateFile = !empty($this->conf['template']) ? 'uploads/tx_ubleipzigbooking/' . $this->conf['template'] : $this->conf['templateFile'];
        $this->templateCode = $this->cObj->fileResource($templateFile);

        // Including CSS

        $cssFile = $this->getRelativeFileName($this->conf['cssFile']);
        $GLOBALS["TSFE"]->additionalHeaderData['ubleipzigbooking_pi1'] = '<link rel="stylesheet" type="text/css" href="' . $cssFile . '" />';
        return;
    }

    function getRelativeFileName($filename)
    {
        if (substr($filename, 0, 4) == 'EXT:')
        {
            list($extKey, $local) = explode('/', substr($filename, 4) , 2);
            $filename = '';
            if (strcmp($extKey, '') && t3lib_extMgm::isLoaded($extKey) && strcmp($local, ''))
            {
                return t3lib_extMgm::siteRelPath($extKey) . $local;
            }
        }
        else return $filename;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_pi1.php'])
{
    include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_pi1.php']);

}

?>