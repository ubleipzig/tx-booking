<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_openinghours',
        'label' => 'week_day',
        'label_userFunc' => 'Ubl\Booking\Library\Tca->getDayTitle',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'enablecolumns' => [
            'disabled' => 'hidden'
        ],
        'week_day' => 'weekDay',
        'closing_days' => 'closingDays',
        'opening_hours' => 'openingHours'
    ],
    'types' => [
        '1' => ['showitem' => 'week_day,hours']
    ],
    'columns' => [
        'week_day' => [
            'label' => $ll . 'tca.tx_booking_domain_model_openinghours.week_day',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'size' => 1,
                'itemsProcFunc' => 'Ubl\Booking\Library\Tca->getDays',
            ]
        ],
        'hours' => [
            'label' => $ll . 'tca.tx_booking_domain_model_openinghours.hours',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingleBox',
                'size' => 10,
                'itemsProcFunc' => 'Ubl\Booking\Library\Tca->getHours',
                'minitems' => 0,
                'maxitems' => 9999,
            ]
        ]
    ]
];
