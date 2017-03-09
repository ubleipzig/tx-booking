<?php
/**
 * ext_tables.php
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
		'label_alt' => 'date',
		'label_alt_force' => 'true',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'name' => 'name',
		'description' => 'description',
		'date' => 'date',
		'closingday' => 'closingday',
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

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Bookings',
	'Bookings for Rooms'
);
