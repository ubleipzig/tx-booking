<?php
defined('TYPO3_MODE') or die();

$ll = 'LLL:EXT:booking/Resources/Private/Language/locallang.xlf:';

return array(
    'ctrl' => array(
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
        'enablecolumns' => array(
            'disabled'      => 'hidden'
        )
    ),
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
            'label' => $ll .'tca.tx_booking_domain_model_closingday.name',
            'config' => array(
                'type' => 'input',
                'size' => 20,
                'max' => 256,
                'eval' => 'required'
            )
        ),
        'date' => array(
            'label' => $ll . 'tca.tx_booking_domain_model_closingday.date',
            'config' => array(
                'type' => 'input',
                'size' => 5,
                'eval' => 'date,required',
            )
        ),
        'description' => array(
            'label' => $ll . 'tca.tx_booking_domain_model_closingday.description',
            'config' => array(
                'type' => 'text',
                'cols' => 20,
                'rows' => 2
            )
        ),
    )
);
