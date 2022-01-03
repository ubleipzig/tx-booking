<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:';

return [
    "ctrl" => [
        'title' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_booking',
        'label' => 'time',
        'label_alt' => 'fe_user',
        'label_alt_force' => 'true',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'sortby' => 'sorting',
        'enablecolumns' => [
            'disabled' => 'hidden',
        ]
    ],
    "interface" => [
        "showRecordFieldList" => "hidden,room,time,fe_user, Comment"
    ],
    /*"feInterface" => [
        "fe_admin_fieldList" => "hidden, fe_user",
    ],*/
    "types" => [
        "0" => [
            "showitem" => "hidden, --palette--;;1, room, time, fe_user, comment"
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
        "room" => [
            'suppress_icons' => '1',
            "exclude" => 1,
            "label" => $ll . "tca.tx_booking_domain_model_room.name",
            "config" => [
                "type" => "select",
                'renderType' => 'selectSingle',
                "foreign_table" => "tx_booking_domain_model_room",
                "foreign_table_where" => "and (tx_booking_domain_model_room.pid=###CURRENT_PID### OR tx_booking_domain_model_room.booking_storage=###CURRENT_PID###) order by tx_booking_domain_model_room.name",
                "size" => "1",
                "minitems" => 1,
                "maxitems" => 1,
            ]
        ],
        "time" => [
            "exclude" => 1,
            "label" => $ll . "tca.tx_booking_domain_model_booking.hour",
            "config" => [
                "type" => "input",
                "size" => "8",
                "renderType" => "inputDateTime",
                "eval" => "datetime, required",
                "checkbox" => "0",
                "default" => "0"
            ]
        ],
        "fe_user" => [
            "exclude" => 1,
            "label" => $ll . "tca.tx_booking_domain_model_booking.feuser",
            "config" => [
                "type" => "select",
                "renderType" => "selectSingle",
                "items" => [
                    [
                        '',
                        0
                    ]
                ],
                "foreign_table" => "fe_users",
                "foreign_table_where" => " order by fe_users.name",
                "size" => "1",
                "minitems" => 0,
                "maxitems" => 1,
            ]
        ],
        'comment' => [
            'exclude' => 1,
            "label" => $ll . 'tca.tx_booking_domain_model_booking.comment',
            'config' => [
                'type' => 'text',
                'cols' => '40',
                'rows' => '6',
                'wrap' => 'off'
            ]
        ],
    ]
];
