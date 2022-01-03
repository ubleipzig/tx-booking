<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:';

return [
    "ctrl" => [
        'title' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_room',
        'label' => 'name',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ],
        'foreign_table_loadIcon' => '1'
    ],
    "interface" => [
        "showRecordFieldList" => "hidden,name,opening_hours,closing_days"
    ],
    /*"feInterface" => [
        "fe_admin_fieldList" => "hidden, name, hours",
    ],*/
    // 1-1-1 = style pointer which defines color, style and border
    "types" => [
        "0" => [
            "showitem" => "hidden, --palette--;;1,name,opening_times_storage,booking_storage"
        ]
    ],
    "palettes" => [
        "1" => [
            "showitem" => ""
        ]
    ],
    "columns" => [
        "hidden" => [
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
            "config" => [
                "type" => "check",
                "default" => "0"
            ]
        ],
        "name" => [
            "label" => $ll . "tca.tx_booking_domain_model_room.name",
            "config" => [
                "type" => "input",
                "size" => "30",
                "eval" => "required,trim",
            ]
        ],
        "opening_times_storage" => [
            "label" => $ll . "tca.tx_booking_domain_model_room.opening_times_storage",
            "config" => [
                'type' => 'group',
                'size' => '3',
                'autoSizeMax' => 10,
                'maxitems' => '200',
                'minitems' => '0',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'wizards' => []
            ],
        ],
        "booking_storage" => [
            "label" => $ll . "tca.tx_booking_domain_model_room.booking_storage",
            "config" => [
                'type' => 'group',
                'size' => '1',
                'autoSizeMax' => 10,
                'maxitems' => '1',
                'minitems' => '0',
                'internal_type' => 'db',
                'allowed' => 'pages',
                'wizards' => []
            ]
        ]
    ]
];
