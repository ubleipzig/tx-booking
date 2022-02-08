<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

// Adds flexform for tx_booking
$pluginSignature = 'booking_bookings';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:booking/Configuration/FlexForms/Bookings.xml'
);