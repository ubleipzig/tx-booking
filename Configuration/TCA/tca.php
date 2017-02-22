<?php
$TCA["tx_ubleipzigbooking_object"] = Array(
	"ctrl" => $TCA["tx_ubleipzigbooking_object"]["ctrl"],
	"interface" => Array(
		"showRecordFieldList" => "hidden,name,opening_hours,closing_days"
	),
	"feInterface" => $TCA["tx_ubleipzigbooking_flat"]["feInterface"],
	"columns" => Array(
		"hidden" => Array(
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.xml:LGL.hidden",
			"config" => Array(
				"type" => "check",
				"default" => "0"
			)
		),
		"name" => Array(
			"label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object.name",
			"config" => Array(
				"type" => "input",
				"size" => "30",
				"eval" => "required,trim",
			)
		),
		"opening_times_storage" => array(
			"label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object.opening_times_storage",
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
			"label" => "LLL:EXT:ubleipzigbooking/locallang_db.xml:tx_ubleipzigbooking_object.booking_storage",
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
			),
		)
	),

	// 1-1-1 = style pointer which defines color, style and border

	"types" => Array(
		"0" => Array(
			"showitem" => "hidden;;1;;1-1-1, name,opening_times_storage,booking_storage"
		)
	),
	"palettes" => Array(
		"1" => Array(
			"showitem" => ""
		)
	)
);

$TCA['tx_ubleipzigbooking_domain_model_closingday'] = array(
	'ctrl' => $TCA['tx_ubleipzigbooking_domain_model_closingday']['ctrl'],
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
			'label' => 'LLL:EXT:ubleipzigbooking/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_closingday.name',
			'config' => array(
				'type' => 'input',
				'size' => 20,
				'max' => 256
			)
		),
		'date' => array(
			'label' => 'LLL:EXT:ubleipzigbooking/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_closingday.date',
			'config' => array(
				'type' => 'input',
				'size' => 5,
				'eval' => 'date',
			)
		),
		'description' => array(
			'label' => 'LLL:EXT:ubleipzigbooking/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_closingday.description',
			'config' => array(
				'type' => 'text',
				'cols' => 20,
				'rows' => 2
			)
		),
	)
);

$TCA['tx_ubleipzigbooking_domain_model_dutyhours'] = array(
	'ctrl' => $TCA['tx_ubleipzigbooking_domain_model_dutyhours']['ctrl'],
	'types' => array(
		'1' => array('showitem' => 'week_day,hours')
	),
	'columns' => array(
		'week_day' => array(
			'label' => 'LLL:EXT:ubleipzigbooking/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_dutyhours.week_day',
			'config' => array(
				'type' => 'select',
				'size' => 1,
				'itemsProcFunc' => '\LeipzigUniversityLibrary\ubleipzigbooking\Library\Tca->getDays',
			)
		),
		'hours' => array(
			'label' => 'LLL:EXT:ubleipzigbooking/Resources/Private/Language/locallang_db.xml:tx_ubleipzigbooking_domain_model_dutyhours.hours',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingleBox',
				'size' => 10,
				'itemsProcFunc' => '\LeipzigUniversityLibrary\ubleipzigbooking\Library\Tca->getHours',
				'minitems' => 1,
				'maxitems' => 9999,
			)
		),
	)
);