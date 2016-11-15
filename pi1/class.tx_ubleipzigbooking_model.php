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
*  (c) 2007 Joachim Ruhs <postmaster@joachim-ruhs.de>
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

class tx_ubleipzigbooking_model

/*extends tslib_pibase*/

{
    /* Set Globals Configuration ... */
    function setConfiguration($conf)
    {
        $this->conf = $conf;
    }

    function setGPVars($gp)
    {
        $this->_GP = $gp;
    }

    /* Default Setters & Getters */
    function getUid()
    {
        if ($this->uid === NULL) $this->refresh();
        return $this->uid;
    }

    function setUid($id)
    {
        $this->uid = $id;
    }

    function getPid()
    {
        if ($this->uid === NULL) $this->refresh();
        return $this->pid;
    }

    /*
    * delete booking that are older than one year
    *
    */
    function deleteOldBookings()
    {
        $GLOBALS['TYPO3_DB']->debugOutput = TRUE;
        $where = 'startdate < ' . (time() - 3600 * 24 * 365);
        $result = $GLOBALS['TYPO3_DB']->exec_DELETEquery('tx_ubleipzigbooking', $where);
    }

    /*
    * returns a formatted currency with decimal- and thousands-separator as
    * string
    *
    * @return	$s		string
    *
    * @params  $v		string
    *
    */
    function formatC($v)
    {
        $s = ($this->conf['decimalSeparator'] == ',') ? number_format($v, 2, ',', '.') : number_format($v, 2, '.', ',');
        return $s;
    }

    /*
    * returns a formatted number with thousands-separator as
    * string
    *
    * @return	$s		string
    *
    * @params  $v		string
    *
    */
    function formatN($v)
    {
        $s = ($this->conf['decimalSeparator'] == ',') ? number_format($v, 0, ',', '.') : number_format($v, 0, '.', ',');
        return $s;
    }
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_model.php'])
{
    include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_model.php']);

}

?>