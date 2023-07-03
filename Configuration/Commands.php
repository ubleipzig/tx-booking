<?php

/**
 * Configuration Commands
 *
 * Copyright (C) Leipzig University Library 2023 <info@ub.uni-leipzig.de>
 *
 * @author  Frank Morgner <morgnerf@ub.uni-leipzig.de>
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

/**
 * Commands to be executed by TYPO3, where the key of the array
 * is the name of the command (to be called as the first argument after "typo3").
 * Required parameter is the "class" of the command which needs to be a subclass
 * of \Symfony\Component\Console\Command\Command.
 *
 * This file is deprecated in TYPO3 v10 and will be removed in TYPO3 v11.
 * See Deprecation: #89139 - Console Commands configuration format Commands.php
 * https://docs.typo3.org/c/typo3/cms-core/master/en-us/Changelog/10.3/Deprecation-89139-ConsoleCommandsConfigurationFormatCommandsPhp.html
 */
return [
    'booking:cleanup' => [
        'class' => Ubl\Booking\Command\CleanupCommand::class
    ],
];