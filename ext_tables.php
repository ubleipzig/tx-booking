<?php
if (!defined('TYPO3_MODE')) die('Access denied.');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ublbooking_domain_model_room');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_ublbooking_domain_model_room');
$TCA["tx_ublbooking_domain_model_room"] = array(
	"ctrl" => array(
		'title' => 'LLL:EXT:ubl_booking/Resources/Private/Language/locallang.xlf:tca.tx_ublbooking_domain_model_room',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'foreign_table_loadIcon' => '1',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tca.php',
	),
	"feInterface" => array(
		"fe_admin_fieldList" => "hidden, name, hours",
	)
);
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ublbooking_domain_model_booking');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_ublbooking_domain_model_booking');
$TCA["tx_ublbooking_domain_model_booking"] = array(
	"ctrl" => array(
		'title' => 'LLL:EXT:ubl_booking/Resources/Private/Language/locallang.xlf:tca.tx_ublbooking_domain_model_booking',
		'label' => 'time',
		'label_alt' => 'fe_user',
		'label_alt_force' => 'true',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tca.php',
	),
	"feInterface" => array(
		"fe_admin_fieldList" => "hidden, fe_user",
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ublbooking_domain_model_closingday');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_ublbooking_domain_model_closingday');
$TCA["tx_ublbooking_domain_model_closingday"] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:ubl_booking/Resources/Private/Language/locallang.xlf:tca.tx_ublbooking_domain_model_closingday',
		'label' => 'name',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'name' => 'name',
		'description' => 'description',
		'date' => 'date',
		'closingday' => 'closingday',
		'delete'        => 'deleted',
		'enablecolumns' => array(
			'disabled'      => 'hidden'
		),
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tca.php',
	)
);

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_ublbooking_domain_model_openinghours');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_ublbooking_domain_model_openinghours');
$TCA["tx_ublbooking_domain_model_openinghours"] = array(
	'ctrl' => array(
		'title' => 'LLL:EXT:ubl_booking/Resources/Private/Language/locallang.xlf:tca.tx_ublbooking_domain_model_openinghours',
		'label' => 'week_day',
		'label_userFunc' => '\LeipzigUniversityLibrary\UblBooking\Library\Tca->getDayTitle',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'week_day' => 'weekDay',
		'closing_days' => 'closingDays',
		'opening_hours' => 'openingHours',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/tca.php',
	)
);

$pluginSignature = 'ublbooking_bookings';
$TCA['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
	$pluginSignature,
	'FILE:EXT:' . $_EXTKEY . '/Configuration/FlexForms/Bookings.xml'
);

// todo: mouseover hints for flexforms
// \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr(
// 	'tt_content.pi_flexform.' . $pluginSignature . '.list',
// 	'EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_csh_flexform.xlf'
// );

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Bookings',
	'Bookings for Rooms'
);
