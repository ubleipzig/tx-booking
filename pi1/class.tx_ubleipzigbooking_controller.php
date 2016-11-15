<?php
/***************************************************************
*  Copyright notice
*
*  Forked and modified by Fabian Heusel <fheusel@posteo.de>
*
***************************************************************/
/***************************************************************
*  Copyright notice
*
*  (c) 2008-2012 Joachim Ruhs <postmaster@joachim-ruhs.de>
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
 * Plugin 'Object manager' for the 'ubleipzigbooking' extension.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 * @author Fabian Heusel <fheusel@posteo.de>
 */

if (!class_exists('tslib_pibase')) require_once (PATH_tslib . 'class.tslib_pibase.php');

require_once (t3lib_extMgm::extPath('ubleipzigbooking') . "pi1/class.tx_ubleipzigbooking_view.php");

require_once (t3lib_extMgm::extPath('ubleipzigbooking') . "pi1/class.tx_ubleipzigbooking_model.php");

class tx_ubleipzigbooking_controller

{
    /* Extension Stuff */
    var $prefixId = 'tx_ubleipzigbooking_pi1';
    var $scriptRelPath = 'pi1/class.tx_ubleipzigbooking_controller.php';
    var $extKey = 'ubleipzigbooking';
    /* Global Configuration */
    var $conf;
    var $_GP;
    /* Set Code */
    function setCode($code)
    {
        $this->code = $code;
    }

    function setConfiguration($conf)
    {
        $this->conf = $conf;
    }

    function setGPVars($GP)
    {
        $this->_GP = $GP;
    }

    function setLocalLang(&$LOCAL_LANG)
    {
        $this->LOCAL_LANG = $LOCAL_LANG;
    }

    /* Main Function for processing Admin Controls */
    function handle()
    {

        // Configure caching

        $this->allowCaching = $this->conf["allowCaching"] ? 1 : 0;
        if (!$this->allowCaching)
        {
            $GLOBALS["TSFE"]->set_no_cache();
        }

        $view = tx_ubleipzigbooking_view::makeInstance("tx_ubleipzigbooking_view", $this->conf);
        $view->setLocalLang($this->LOCAL_LANG);
        $this->conf['feUserUid'] = $GLOBALS['TSFE']->fe_user->user['uid'];
        switch ($this->_GP['action'])
        {
        case 'viewMonth':
            $model = new tx_ubleipzigbooking_model();
            $model->setConfiguration($this->conf);

            // delete old booking data

            $model->deleteOldBookings();
            $view->setConfiguration($this->conf);
            if ($this->_GP['month'] == '') $this->_GP['month'] = (int)date('m', time());
            if ($this->_GP['year'] == '') $this->_GP['year'] = date('Y', time());
            $view->setGPvars($this->_GP);
            $view->setCode("VIEWMONTH");
            $view->setInput($data);
            $out = $view->display();
            return $errors . $out;
            break;

        case 'viewWeek':
            $model = new tx_ubleipzigbooking_model();
            $model->setConfiguration($this->conf);

            // delete old booking data

            $model->deleteOldBookings();
            $view->setConfiguration($this->conf);
            if ($this->_GP['month'] == '') $this->_GP['month'] = (int)date('m', time());
            if ($this->_GP['year'] == '') $this->_GP['year'] = date('Y', time());
            $view->setGPvars($this->_GP);
            $view->setCode("VIEWWEEK");
            $view->setInput($data);
            $out = $view->display();
            return $errors . $out;
            break;

        default:
        }
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_controller.php'])
{
    include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_controller.php']);

}

?>