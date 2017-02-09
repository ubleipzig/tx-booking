<?php

if (!defined('TYPO3_MODE')) die('Access denied.');
t3lib_extMgm::allowTableOnStandardPages('tx_ubleipzigbooking_object');
t3lib_extMgm::addToInsertRecords('tx_ubleipzigbooking_object');
$TCA["tx_ubleipzigbooking_object"] = array(
	"ctrl" => array(
		'title' => 'LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object',
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
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_ubleipzigbooking_object.gif',
	),
	"feInterface" => array(
		"fe_admin_fieldList" => "hidden, name, hours",
	)
);
t3lib_extMgm::allowTableOnStandardPages('tx_ubleipzigbooking');
t3lib_extMgm::addToInsertRecords('tx_ubleipzigbooking');
$TCA["tx_ubleipzigbooking"] = array(
	"ctrl" => array(
		'title' => 'LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking',
		'label' => 'startdate',
		'label_alt' => 'feuseruid',
		'label_alt_force' => 'true',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY) . 'tca.php',
		'iconfile' => t3lib_extMgm::extRelPath($_EXTKEY) . 'icon_tx_ubleipzigbooking.gif',
	),
	"feInterface" => array(
		"fe_admin_fieldList" => "hidden, feuseruid",
	)
);

$TCA["tx_ubleipzigbooking_domain_model_closingday"] = array(
	'ctrl' => array(
		'title'         => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_closingday',
		'label'         => 'name',
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
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/ext_icon.gif'
	)
);

t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY . '_pi1'] = 'layout,select_key';

t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ubleipzigbooking/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1'
), 'list_type');

// ab hier flexform

$TCA['tt_content']['types']['list']['subtypes_addlist'][$_EXTKEY . '_pi1'] = 'pi_flexform';

// Wir definieren die Datei, die unser Flexform Schema enthält

t3lib_extMgm::addPiFlexFormValue($_EXTKEY . '_pi1', 'FILE:EXT:' . $_EXTKEY . '/flexform_ds.xml');
t3lib_extMgm::addPlugin(Array(
	'LLL:EXT:ubleipzigbooking/locallang_db.php:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1'
), 'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY, 'pi1/static/', 'Default CSS-Styles');

// this is new
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Bookings',
	'Bookings for Rooms'
);