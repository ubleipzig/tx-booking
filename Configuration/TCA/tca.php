<?php
$TCA["tx_booking_domain_model_room"] = array(
	"ctrl" => $TCA["tx_booking_domain_model_room"]["ctrl"],
	"interface" => array(
		"showRecordFieldList" => "hidden,name,opening_hours,closing_days"
	),
	"feInterface" => $TCA["tx_booking_flat"]["feInterface"],
	"columns" => array(
		"hidden" => array(
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => array(
				"type" => "check",
				"default" => "0"
			)
		),
		"name" => array(
			"label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_room.name",
			"config" => array(
				"type" => "input",
				"size" => "30",
				"eval" => "required,trim",
			)
		),
		"opening_times_storage" => array(
			"label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_room.opening_times_storage",
			"config" => array(
				'type' => 'group',
				'size' => '3',
				'autoSizeMax' => 10,
				'maxitems' => '200',
				'minitems' => '0',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'show_thumbs' => '1',
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					)
				)
			),
		),
		"booking_storage" => array(
			"label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_room.booking_storage",
			"config" => array(
				'type' => 'group',
				'size' => '1',
				'autoSizeMax' => 10,
				'maxitems' => '1',
				'minitems' => '0',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'show_thumbs' => '1',
				'wizards' => array(
					'suggest' => array(
						'type' => 'suggest',
					)
				)
			)
		)
	),

	// 1-1-1 = style pointer which defines color, style and border
	"types" => array(
		"0" => array(
			"showitem" => "hidden;;1;;1-1-1, name,opening_times_storage,booking_storage"
		)
	),
	"palettes" => array(
		"1" => array(
			"showitem" => ""
		)
	)
);

$TCA['tx_booking_domain_model_closingday'] = array(
	'ctrl' => $TCA['tx_booking_domain_model_closingday']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,date,description'
	),
	'types' => array(
		'1' => array('showitem' => 'name,date,description')
	),
	'palettes' => array(
		'1' => array('showitem' => 'name,date,description')
	),
	'columns' => array(
		'name' => array(
			'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_closingday.name',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'max' => 256,
				'eval' => 'required'
			)
		),
		'date' => array(
			'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_closingday.date',
			'config' => array(
				'type' => 'input',
				'size' => 5,
				'eval' => 'date,required',
			)
		),
		'description' => array(
			'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_closingday.description',
			'config' => array(
				'type' => 'text',
				'cols' => 20,
				'rows' => 2
			)
		),
	)
);

$TCA['tx_booking_domain_model_openinghours'] = array(
	'ctrl' => $TCA['tx_booking_domain_model_openinghours']['ctrl'],
	'types' => array(
		'1' => array('showitem' => 'week_day,hours')
	),
	'columns' => array(
		'week_day' => array(
			'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_openinghours.week_day',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'size' => 1,
				'itemsProcFunc' => 'Ubl\Booking\Library\Tca->getDays',
			)
		),
		'hours' => array(
			'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_openinghours.hours',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingleBox',
				'size' => 10,
				'itemsProcFunc' => 'Ubl\Booking\Library\Tca->getHours',
				'minitems' => 0,
				'maxitems' => 9999,
			)
		),
	)
);

$TCA["tx_booking_domain_model_booking"] = array(
    "ctrl" => $TCA["tx_booking_domain_model_booking"]["ctrl"],
	"interface" => array(
        "showRecordFieldList" => "hidden,room,time,fe_user, Comment"
    ) ,
    "feInterface" => $TCA["tx_booking_book"]["feInterface"],
    "columns" => array(
        "hidden" => array(
            "exclude" => 1,
            "label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
            "config" => array(
                "type" => "check",
                "default" => "0"
            )
        ) ,
        "room" => array(
            'suppress_icons' => '1',
            "exclude" => 1,
            "label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_room.name",
            "config" => array(
                "type" => "select",
                "foreign_table" => "tx_booking_domain_model_room",
				"foreign_table_where" => "and (tx_booking_domain_model_room.pid=###CURRENT_PID### OR tx_booking_domain_model_room.booking_storage=###CURRENT_PID###) order by tx_booking_domain_model_room.name",
                "size" => "1",
                "minitems" => 1,
                "maxitems" => 1,
            )
        ) ,
        "time" => array(
            "exclude" => 1,
            "label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_booking.hour",
            "config" => array(
                "type" => "input",
                "size" => "8",
                "max" => "20",
                "eval" => "datetime, required",
                "checkbox" => "0",
                "default" => "0"
            )
        ),
        "fe_user" => array(
            "exclude" => 1,
            "label" => "LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_booking.feuser",
            "config" => array(
                "type" => "select",
                "items" => array(
                    array(
                        '',
                        0
                    ) ,
                ) ,
                "foreign_table" => "fe_users",
                "foreign_table_where" => " order by fe_users.name",
				"size" => "1",
				"minitems" => 0,
				"maxitems" => 1,
            )
        ) ,
        'comment' => array(
            'exclude' => 1,
            "label" => 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:tca.tx_booking_domain_model_booking.comment',
            'config' => array(
                'type' => 'text',
                'cols' => '40',
                'rows' => '6',
                'wrap' => 'off'
            )
        ) ,
    ) ,
    "types" => array(
        "0" => array(
            "showitem" => "hidden;;1;;1-1-1, room, time, fe_user, comment"
        ) ,
    ) ,
    "palettes" => array(
        "1" => array(
            "showitem" => ""
        )
    )
);
