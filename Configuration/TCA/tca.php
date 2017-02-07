<?php
$TCA['tx_ubleipzigbooking_domain_model_closingday'] = array(
	'ctrl' => $TCA['tx_ubleipzigbooking_domain_model_closingday']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'name,date,closingday,description'
	),
	'types' => array(
		'1' => array('showitem' => 'name,date,closingday,description')
	),
	'palettes' => array(
		'1' => array('showitem' => 'name,description,date')
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