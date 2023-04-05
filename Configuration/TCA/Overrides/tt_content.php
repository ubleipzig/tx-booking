<?php
if (!defined('TYPO3_MODE')) {
    die ('Access denied.');
}

foreach (['room', 'booking', 'closingday', 'openinghours'] as $table) {
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_booking_domain_model_' . $table);
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToInsertRecords('tx_booking_domain_model_' . $table);
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Ubl.Booking',
    'Bookings',
    'Bookings for Rooms'
);

// Adds flexform for tx_booking
$pluginSignature = 'booking_bookings';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:booking/Configuration/FlexForms/Bookings.xml'
);