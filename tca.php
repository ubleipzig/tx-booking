<?php

if (!defined('TYPO3_MODE')) die('Access denied.');
$TCA["tx_ubleipzigbooking_object"] = Array(
    "ctrl" => $TCA["tx_ubleipzigbooking_object"]["ctrl"],
    "interface" => Array(
        "showRecordFieldList" => "hidden,name,hours"
    ) ,
    "feInterface" => $TCA["tx_ubleipzigbooking_flat"]["feInterface"],
    "columns" => Array(
        "hidden" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
            "config" => Array(
                "type" => "check",
                "default" => "0"
            )
        ) ,
        "name" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object.name",
            "config" => Array(
                "type" => "input",
                "size" => "30",
                "eval" => "required,trim",
            )
        ) ,
        "hours" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object.hours",
            "config" => Array(
                "type" => "input",
                "size" => "40",
                "default" => "8,9,10,11,12,13,14,15,16,17,18,19,20,21,22",
                "eval" => "required,trim",
            )
        ) ,
    ) ,

    // 1-1-1 = style pointer which defines color, style and border

    "types" => Array(
        "0" => Array(
            "showitem" => "hidden;;1;;1-1-1, name,hours"
        )
    ) ,
    "palettes" => Array(
        "1" => Array(
            "showitem" => ""
        )
    )
);
$TCA["tx_ubleipzigbooking"] = Array(
    "ctrl" => $TCA["tx_ubleipzigbooking"]["ctrl"],
    "interface" => Array(
        "showRecordFieldList" => "hidden,objectuid,startdate,enddate,feuseruid, memo"
    ) ,
    "feInterface" => $TCA["tx_ubleipzigbooking_book"]["feInterface"],
    "columns" => Array(
        "hidden" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
            "config" => Array(
                "type" => "check",
                "default" => "0"
            )
        ) ,
        "objectuid" => Array(
            'suppress_icons' => '1',
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking.object",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array(
                        '',
                        0
                    ) ,
                ) ,
                "foreign_table" => "tx_ubleipzigbooking_object",
                "foreign_table_where" => "and tx_ubleipzigbooking_object.pid=###CURRENT_PID### order by tx_ubleipzigbooking_object.name",
                "size" => "1",
                "minitems" => 1,
                "maxitems" => 1,
                "eval" => "trim",
                'wizards' => Array(
                    '_PADDING' => 1,
                    '_VERTICAL' => 1,
                    'edit' => Array(
                        'type' => 'popup',
                        'title' => 'Edit booking objects',
                        'script' => 'wizard_edit.php',
                        'icon' => 'edit2.gif',
                        'popup_onlyOpenIfSelected' => 1,
                        'JSopenParams' => 'height=350,width=580,status=0,menubar=0,scrollbars=1',
                    ) ,
                    'add' => Array(
                        'type' => 'script',
                        'title' => 'Create new booking object',
                        'icon' => 'add.gif',
                        'params' => Array(
                            'table' => 'tx_ubleipzigbooking_object',
                            'pid' => '###CURRENT_PID###',
                            'setValue' => 'prepend'
                        ) ,
                        'script' => 'wizard_add.php',
                    ) ,
                    'list' => Array(
                        'type' => 'script',
                        'title' => 'List booking objects',
                        'icon' => 'list.gif',
                        'params' => Array(
                            'table' => 'ubleipzigbooking_object',
                            'pid' => '###CURRENT_PID###',
                        ) ,
                        'script' => 'wizard_list.php',
                    ) ,
                ) ,
            )
        ) ,
        "startdate" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking.startdate",
            "config" => Array(
                "type" => "input",
                "size" => "8",
                "max" => "20",
                "eval" => "datetime",
                "checkbox" => "0",
                "default" => "0"
            )
        ) ,
        "enddate" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking.enddate",
            "config" => Array(
                "type" => "input",
                "size" => "8",
                "max" => "20",
                "eval" => "datetime",
                "checkbox" => "0",
                "default" => "0"
            )
        ) ,
        "feuseruid" => Array(
            "exclude" => 1,
            "label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking.feuser",
            "config" => Array(
                "type" => "select",
                "items" => Array(
                    Array(
                        '',
                        0
                    ) ,
                ) ,
                "foreign_table" => "fe_users",
                "foreign_table_where" => " order by fe_users.name",
            )
        ) ,
        'memo' => Array(
            'exclude' => 1,
            "label" => 'LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking.memo',
            'config' => Array(
                'type' => 'text',
                'cols' => '40',
                'rows' => '6',
                'wrap' => 'off'
            )
        ) ,
    ) ,
    "types" => Array(
        "0" => Array(
            "showitem" => "hidden;;1;;1-1-1, objectuid, startdate, enddate, feuseruid, memo"
        ) ,
    ) ,
    "palettes" => Array(
        "1" => Array(
            "showitem" => ""
        )
    )
);
?>