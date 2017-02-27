<?php

if (!defined('TYPO3_MODE')) die('Access denied.');

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'LeipzigUniversityLibrary.' . $_EXTKEY,
	'Bookings',
	array('Booking' => 'showWeek, showDay, add, remove'),
	array('Booking' => 'showWeek, showDay, add, remove')
);

// always load TypoScript configuration
\FluidTYPO3\Flux\Core::addStaticTypoScript('EXT:'. $_EXTKEY . '/Configuration/TypoScript/');

