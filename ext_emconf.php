<?php
/***************************************************************
* Extension Manager/Repository config file for ext "ubleipzigbooking".
*
*
* Manual updates:
* Only the data in the array - everything else is removed by next
* writing. "version" and "dependencies" must not be touched!
***************************************************************/
$EM_CONF[$_EXTKEY] = array(
    'title' => 'ubleipzigbooking',
    'description' => 'ubleipzigbooking extension for fe users for rooms.',
    'category' => 'plugin',
    'version' => '0.1.0',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Fabian Heusel',
    'author_email' => 'fheusel@posteo.de',
    'author_company' => 'Universitaet Leipzig',
    'constraints' => array(
        'depends' => array(
            'typo3' => '4.5.0-6.2.99',
            'cms' => '',
        ) ,
        'conflicts' => array() ,
        'suggests' => array() ,
    ) ,
);