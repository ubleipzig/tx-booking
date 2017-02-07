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
    'title' => 'Booking for Rooms',
    'description' => 'book available rooms',
    'category' => 'plugin',
    'version' => '0.2.0',
    'state' => 'beta',
    'uploadfolder' => false,
    'createDirs' => '',
    'clearcacheonload' => true,
    'author' => 'Ulf Seltmann',
    'author_email' => 'seltmann@ub.uni-leipzig.de',
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