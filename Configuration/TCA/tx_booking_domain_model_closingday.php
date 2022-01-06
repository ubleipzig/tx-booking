<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:';

return [
    'ctrl' => [
        'title' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_closingday',
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
        'enablecolumns' => [
            'disabled'      => 'hidden'
        ]
    ],
    'interface' => [
        'showRecordFieldList' => 'name,date,description'
    ],
    'types' => [
        '1' => ['showitem' => 'name,date,description']
    ],
    'palettes' => [
        '1' => ['showitem' => 'name,date,description']
    ],
    'columns' => [
        'name' => [
            'label' => $ll .'tca.tx_booking_domain_model_closingday.name',
            'config' => [
                'type' => 'input',
                'size' => 20,
                'max' => 256,
                'eval' => 'required'
            ]
        ],
        'date' => [
            'label' => $ll . 'tca.tx_booking_domain_model_closingday.date',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'renderType' => 'inputDateTime',
                'eval' => 'date,required',
            ]
        ],
        'description' => [
            'label' => $ll . 'tca.tx_booking_domain_model_closingday.description',
            'config' => [
                'type' => 'text',
                'cols' => 20,
                'rows' => 2
            ]
        ],
    ]
];
