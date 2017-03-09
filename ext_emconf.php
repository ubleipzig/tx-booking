<?php
/**
 * ext_emconf.php
 *
 * Copyright (C) Leipzig University Library 2017 <info@ub.uni-leipzig.de>
 *
 * @author  Ulf Seltmann <seltmann@ub.uni-leipzig.de>
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License version 2,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Booking for Rooms',
	'description' => 'Manage bookings of rooms for fe users',
	'category' => 'plugin',
	'version' => '1.0.0',
	'state' => 'stable',
	'uploadfolder' => false,
	'createDirs' => '',
	'clearcacheonload' => true,
	'author' => 'Ulf Seltmann',
	'author_email' => 'seltmann@ub.uni-leipzig.de',
	'author_company' => 'Leipzig University Library',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-6.2.99'
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
);
