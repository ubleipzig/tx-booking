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
*  (c) 2008-2014 Joachim Ruhs <postmaster@joachim-ruhs.de>
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
 * Plugin 'Gallery' for the 'ubleipzigbooking' extension.
 *
 * @author Joachim Ruhs <postmaster@joachim-ruhs.de>
 * @author Fabian Heusel <fheusel@posteo.de>
 */

if (!class_exists('tslib_pibase')) require_once (PATH_tslib . 'class.tslib_pibase.php');

class tx_ubleipzigbooking_view extends tslib_pibase

{
    var $prefixId = 'tx_ubleipzigbooking_pi1';
    function &makeInstance($className, $conf)
    {
        $class = new $className;
        $class->setConfiguration($conf);
        $class->cObj = t3lib_div::makeInstance("tslib_cObj");
        $class->templateCode = $class->cObj->fileResource($conf["templateFile"]);
        $class->pi_loadLL();
        return $class;
    }

    /* Set Configuration */
    function setConfiguration($conf)
    {
        $this->conf = $conf;
    }

    /* Set View Input */
    function setInput($input)
    {
        $this->input = $input;
    }

    /* Set Code */
    function setCode($code)
    {
        $this->code = $code;
    }

    function setGPVars($gp)
    {
        $this->_GP = $gp;
    }

    function setLocalLang(&$LOCAL_LANG)
    {
        $this->LOCAL_LANG = $LOCAL_LANG;
    }

    /* initial call */
    function display()
    {
        $this->template = $this->cObj->fileResource($this->conf["templateFile"]);
        switch ($this->code)
        {
        case 'VIEWMONTH':
            return $this->viewMonth();
            break;

        case 'VIEWWEEK':
            return $this->viewMonth();
            break;

        case 'SHOWMESSAGE':
            return $this->showMessage();
            break;

        default:

            // return $this->displayDefault();

        }
    }

    function viewMonth()
    {
        $template = $this->cObj->getSubpart($this->template, '###MONTHVIEW###');
        $urlBase = /*$GLOBALS['TSFE']->baseUrl .*/
        $this->pi_getPageLink($GLOBALS['TSFE']->id);
        $lengthOfMonth = array(
            1 => 31,
            28,
            31,
            30,
            31,
            30,
            31,
            31,
            30,
            31,
            30,
            31
        );

        // leap year calculating....

        if (date("L", mktime(0, 0, 0, 1, 1, $this->_GP['year'])) == 1)
        {
            $lengthOfMonth[2] = 29;
        }

        $arr = array(
            $this->prefixId . '[action]' => 'viewMonth',
            $this->prefixId . '[objectUid]' => (int)$this->_GP['objectUid'],
            $this->prefixId . '[month]' => (int)$this->_GP['month'] - 1,
            $this->prefixId . '[year]' => (int)$this->_GP['year']
        );
        if ($arr[$this->prefixId . '[month]'] < 1)
        {
            $arr[$this->prefixId . '[month]'] = 12;
            $arr[$this->prefixId . '[year]'] = (int)$this->_GP['year'] - 1;
        }

        $marks['###PREVMONTH###'] = $this->pi_linkTP('<img src="typo3conf/ext/ubleipzigbooking/pi1/res/arrowleft.png"/>', $arr, 1, 0);
        $arr[$this->prefixId . '[month]'] = $this->_GP['month'] + 1;
        if ($arr[$this->prefixId . '[month]'] > 12)
        {
            $arr[$this->prefixId . '[month]'] = 1;
            $arr[$this->prefixId . '[year]'] = (int)$this->_GP['year'] + 1;
        }

        $marks['###MONTH###'] = $this->pi_getLL(date("M", strtotime($this->_GP['year'] . "-" . $this->_GP['month'] . "-01")));
        $marks['###NEXTMONTH###'] = $this->pi_linkTP('<img src="typo3conf/ext/ubleipzigbooking/pi1/res/arrowright.png"/>', $arr, 1, 0);
        if (!$this->conf['showDaysShortcuts'] == 1)
        {

            // display the daynames

            $this->conf['startOfWeek'] = 'monday';
            $out.= '<tr>';
            $out.= ($this->conf['startOfWeek'] == 'sunday') ? '</td><td class="dayNames">' . $this->pi_getLL('Sun') . '</td>' : '';
            $out.= '</td><td class="dayNames">' . $this->pi_getLL('Mon') . '</td><td class="dayNames">' . $this->pi_getLL('Tue') . '</td><td class="dayNames">' . $this->pi_getLL('Wed') . '</td><td class="dayNames">' . $this->pi_getLL('Thu') . '</td><td class="dayNames">' . $this->pi_getLL('Fri') . '</td><td class="dayNames">' . $this->pi_getLL('Sat');
            $out.= ($this->conf['startOfWeek'] == 'monday') ? '</td><td class="dayNames">' . $this->pi_getLL('Sun') . '</td></tr>' : '</td></tr>';
        }

        // calculating the left spaces to get the layout right

        $wd = date('w', strtotime($this->_GP['year'] . "-" . $this->_GP['month'] . "-" . "1"));
        $wd = ($wd == 0) ? 7 : $wd;
        if ($wd != 1)
        {
            for ($s = 1; $s < $wd; $s++)
            {
                $out.= '<td class="noDay">&nbsp;</td>';
            }
        }

        for ($d = 1; $d <= $lengthOfMonth[$this->_GP['month']]; $d++)
        {
            $out.= '<td class="day">' . $d . '</td>';
            $wd = date('w', strtotime($this->_GP['year'] . "-" . $this->_GP['month'] . "-" . $d));
            if ($wd == 0) $out.= '</tr><tr>';
        }

        $out.= '</tr>';
        $marks['###CAL###'] = $out;
        $out = $this->cObj->substituteMarkerArray($template, $marks);

        // return $out;

        return $this->eIDScript();
    }

    function eIDScript()
    {
        if ($this->conf['includejQuery']) $GLOBALS['TSFE']->additionalHeaderData['tx_ubleipzigbooking_pi1'] = '<script src="typo3conf/ext/ubleipzigbooking/pi1/scripts/jquery-1.12.1.min.js" type="text/javascript"></script>';
        $GLOBALS["TSFE"]->additionalHeaderData['tx_ubleipzigbooking_pi1'].= '<script type="text/javascript">
				/*<![CDATA[*/

				function tx_ubleipzigbooking_submit(value, ajaxData, action) {
					if ($("#ubleipzigbookingeIDResult")) {

					}

				var mode = value;

				$.ajax({
					type: "POST",
					url: "index.php?eID=tx_ubleipzigbooking_eID",
					data: {
                        "ref": value,
                        "data": ajaxData,
                        "tx_ubleipzigbooking_pi1[action]": action,
                        "tx_ubleipzigbooking_pi1[day]": value
					}
				}).done(function(msg) {
						$("#ubleipzigbookingeIDResult").html(msg);
					});

				}

				function tx_ubleipzigbooking_viewWeek(year, month, day, objectUid, ajaxData) {
				    if ($("#ubleipzigbookingeIDWeekWorking"))
						$("#ubleipzigbookingeIDWeekWorking").html(\'<img class="working" src="typo3conf/ext/ubleipzigbooking/pi1/res/working.gif"/>\');
				    var day = year + "-" + month + "-" + day + ":" + objectUid;
					tx_ubleipzigbooking_submit(day, ajaxData, \'viewWeek\', 0);
				}

				function tx_ubleipzigbooking_viewBookingForm(year, month, day, objectUid, ajaxData) {
					$("#ubleipzigbookingeIDWorking").html(\'<img class="working" src="typo3conf/ext/ubleipzigbooking/pi1/res/working.gif"/>\');
				    var day = year + "-" + month + "-" + day + ":" + objectUid;
					tx_ubleipzigbooking_submit(day, ajaxData, \'viewBookingForm\', 0);
				}


				function tx_ubleipzigbooking_book(objectUid, startdate, feUserUid, id, ajaxData) {
					var memo = $("#tx_ubleipzigbooking_pi1_memo" + id).val();
					tx_ubleipzigbooking_submit(memo, ajaxData, "bookObject");
				}

				function tx_ubleipzigbooking_delete(objectUid, startdate, feUserUid, id, ajaxData) {
					tx_ubleipzigbooking_submit(0, ajaxData, "delete");
				}

				$.fn.center = function () {
				    this.css("position","fixed");
				    this.css("top", (($(window).height() - this.outerHeight()) / 2) /*+ $(window).scrollTop()*/ + "px");
				    this.css("left", (($(window).width() - this.outerWidth()) / 2) /*+ $(window).scrollLeft()*/ + "px");
				    return this;
				  }

				/*]]>*/
				</script>
				';

        // language for eID

        $this->conf['lang'] = $this->LLkey;

        // eID
        // alternative way to transport _GP vars

        $this->conf['compatVersion'] = $GLOBALS['TYPO3_CONF_VARS']['SYS']['compat_version']; // needed for locallang
        $this->conf['_GP'] = $this->_GP;
        $ajaxData = urlencode(json_encode($this->conf));
        if ($this->_GP['action'] == 'viewMonth')
        {
            $out = '<script type="text/javascript">
				/*<![CDATA[*/
				
					tx_ubleipzigbooking_submit(\'' . $this->_GP['action'] . '\',\'' . $ajaxData . '\', \'viewMonth\', \'feusername\', \'desc\');


 // zeige();

				/*]]>*/
				</script>';
        }

        if ($this->_GP['action'] == 'viewWeek')
        {
            list($day, $month, $year) = explode('-', date('d-m-Y', time()));
            $wd = date('w', time());
            $date = date('d-m-Y', mktime(0, 0, 0, $month, ($day - $wd) , $year));
            list($day, $month, $year) = explode('-', $date);
            $out = '<script type="text/javascript">
				/*<![CDATA[*/
				tx_ubleipzigbooking_viewWeek(\'' . $year . '\',\'' . $month . '\',\'' . $day . '\',\'' . 2 . '\',\'' . $ajaxData . '\');
				/*]]>*/
				</script>';
        }

        $out.= '<div id="ubleipzigbookingeIDResult"><img class="working" src="typo3conf/ext/ubleipzigbooking/pi1/res/working.gif"/></div>';
        return $out;
    }

    function showMessage()
    {
        $message = '<div class="message">';
        $message.= ($this->pi_getLL($this->input[0]) != '') ? $this->pi_getLL($this->input[0]) : $this->input[0];
        $message.= '</div>';
        return $message;
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
        $this->conf['decimalSeparator'] = ',';
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
        $this->conf['decimalSeparator'] = ',';
        $s = ($this->conf['decimalSeparator'] == ',') ? number_format($v, 0, ',', '.') : number_format($v, 0, '.', ',');
        return $s;
    }

    /* Returns the $msg in error div-tags
    *
    * @param   $msg		string, messagestring
    * @return 	String		HTML wrapped error message
    *
    */
    function showError($msg)
    {
        return '<div class="errorMessage">' . 'xpublish_view message: ' . $this->errorMessages[$msg] . '</div>';
    }

    var $errorMessages = array(
        'year input error' => 'You entered an invalid year',
    );
}

if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_view.php'])
{
    include_once ($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ubleipzigbooking/pi1/class.tx_ubleipzigbooking_view.php']);

}

?>