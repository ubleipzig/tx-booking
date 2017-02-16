<?php

if (!defined('TYPO3_MODE')) die('Access denied.');


// # Extending TypoScript from static template uid=43 to set up userdefined tag:
/* t3lib_extMgm::addTypoScript($_EXTKEY, 'editorcfg', '
	tt_content.CSS_editor.ch.tx_ubleipzigbooking_pi1 = < plugin.tx_ubleipzigbooking_pi1.CSS_editor
', 43);

// list_type 0 USER_INT object not cached to work correct with xajax
// list_type 1 = USER object cached
t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_ubleipzigbooking_pi1.php', '_pi1', 'list_type', 1);

// eID

$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['tx_ubleipzigbooking_eID'] = 'EXT:ubleipzigbooking/pi1/class.tx_ubleipzigbooking_eID.php';
*/

// new from here
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'LeipzigUniversityLibrary.' . $_EXTKEY,
	'Bookings',
	array('Week' => 'show', 'Day' => 'show, addBooking, removeBooking'),
	array('Week' => 'show', 'Day' => 'show, addBooking, removeBooking')
);

// always load TypoScript configuration
\FluidTYPO3\Flux\Core::addStaticTypoScript('EXT:'. $_EXTKEY . '/Configuration/TypoScript/');

