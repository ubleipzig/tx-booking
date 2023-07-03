<?php
declare(strict_types=1);
/**
 * Class CleanupCommand
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
namespace Ubl\Booking\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use Ubl\Booking\Domain\Repository\Booking;
use Ubl\Booking\Library\Week;

/**
 * Class CleanupCommandController
 *
 * Provides commandline interface to cleanup past bookings
 *
 * @package Ubl\Booking\Command
 */
class CleanupCommand extends Command
{
    /**
     * Repository of bookings
     *
     * @Extbase\Inject
     * @var \Ubl\Booking\Domain\Repository\Booking
     */
    protected $bookingRepository;

    /**
     * Size of chunk for large scales sql queries
     *
     * @var int sqlOperatingChunksize
     * @access protected
     */
    protected $sqlOperatingChunksize = 5000;

    /**
     * Name of table
     *
     * @var string $tableName
     * @access protected
     */
    protected $tableName = 'tx_booking_domain_model_booking';

    /**
     * Configure the command by defining the name, options and arguments
     *
     * @return void
     */
    public function configure()
    {
        $this
            ->setDescription('Removes booking of rooms at a defined interval.')
            ->setHelp('')
            ->addOption(
                'weeks',
                'w',
                InputOption::VALUE_REQUIRED,
                'Pass this option to set time interval defined in weeks for removing expired bookings. Default is 2.',
                '2'
            )->addOption(
                'dry-run',
                null,
                InputOption::VALUE_NONE,
                'If this option is set, the files will not be processed.'
            );
    }

    /**
     * Console command for removing expired bookings
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isDryRun = $input->getOption('dry-run') != false ? true : false;
        $this->objectManager = GeneralUtility::makeInstance(ObjectManager::class);
        $this->bookingRepository = $this->objectManager->get(Booking::class);

        $io = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());
        if ($isDryRun === true) {
            $io->writeln('<info>This is a dry-run. Data will not be removed.</info>');
        }
        $weeks = $input->getOption('weeks');

        $today = new Week();
        $time = $today->sub(new \DateInterval("P{$weeks}W"));
        $bookingList = $this->bookingRepository->findBeforeTime($time);
        $uids = array_chunk(
            array_column($bookingList, 'uid'),
            $this->sqlOperatingChunksize
        );
        $cnt = 0;
        if ($isDryRun === false) {
            foreach ($uids as $package) {
                $cnt += $this->bookingRepository->removeUsersByIds($package);
                sleep(5);
            }
        } else {
            $cnt = count($bookingList);
         }
        $io->writeln(
            sprintf('%d bookings removed before %s', $cnt, $time->format('d-m-y H:i:s T (e, \G\M\T P)'))
       );
       return 0;
    }
}