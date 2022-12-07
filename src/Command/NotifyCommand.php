<?php

declare(strict_types=1);

namespace Vigotech\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Vigotech\Service\EventFetcher\EventFetcher;
use Vigotech\Service\EventFetcher\EventFetcherException;
use Vigotech\Service\EventNotifier\EventNotifier;
use Vigotech\Service\EventNotifier\EventNotifierException;
use Vigotech\Service\GroupFetcher\GroupFetcher;

class NotifyCommand extends Command
{
    private GroupFetcher $groupsFetcher;

    private EventFetcher $eventFetcher;

    private EventNotifier $eventNotifier;

    private LoggerInterface $logger;

    protected static $defaultName = 'vigotech:notify';

    public function __construct(
        GroupFetcher $groupFetcher,
        EventFetcher $eventFetcher,
        EventNotifier $eventNotifier,
        LoggerInterface $logger
    ) {
        $this->groupsFetcher = $groupFetcher;
        $this->eventFetcher  = $eventFetcher;
        $this->eventNotifier = $eventNotifier;
        $this->logger        = $logger;

        parent::__construct();
    }

    public function configure(): void
    {
        $this
            ->addOption(
                'month',
                'M',
                InputOption::VALUE_NONE,
                'Notifies events for current month'
            )
            ->addOption(
                'weekly',
                'w',
                InputOption::VALUE_NONE,
                'Notifies events for next 7 days'
            )
            ->addOption(
                'daily',
                'd',
                InputOption::VALUE_NONE,
                'Notifies today\'s events'
            )
            ->addOption(
                'upcoming',
                'u',
                InputOption::VALUE_NONE,
                'Notifies upcoming events'
            )
            ->addOption(
                'preview',
                'p',
                InputOption::VALUE_NONE,
                'Set preview mode: Push messages to CLI. Don\'t publish anything'
            )
            ->setDescription('Notify users about events via defined notifiers')
            ->setHelp('Notify users about events via enabled notifiers (added at config/services.yaml)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->checkValidOptions($input->getOptions());
        } catch (InvalidOptionException $exception) {
            $output->writeln('<error>'.$exception->getMessage().'</error>');

            return -1;
        }

        $month    = $input->getOption('month');
        $weekly   = $input->getOption('weekly');
        $daily    = $input->getOption('daily');
        $upcoming = $input->getOption('upcoming');

        $this->eventNotifier->setPreviewMode($input->getOption('preview'));

        $groupCollection = $this->groupsFetcher->getGroups();

        $io->section('Obtaining events');

        foreach ($groupCollection->getItems() as $group) {
            foreach ($group->getEventTypes() as $eventType) {
                try {
                    $this->eventFetcher->fetch($group, $eventType);
                } catch (EventFetcherException $eventFetcherException) {
                    $this->logger->error(
                        $eventFetcherException->getType(),
                        ['message' => $eventFetcherException->getMessage()]
                    );
                }
            }
        }

        $events = $this->eventFetcher->getEvents();

        try {
            if ($month) {
                $io->section('Starting monthly notifications');
                $monthEvents = $events->filterMonth();
                if (0 === count($monthEvents)) {
                    $io->warning('No events to notify!');
                } else {
                    $io->success(sprintf('Found %d events. Starting notifications', count(($monthEvents))));
                }
                $this->eventNotifier->notifyWeekly($monthEvents);
            }

            if ($weekly) {
                $io->section('Starting weekly notifications');
                $weeklyEvents = $events->filterWeek();
                if (0 === count($weeklyEvents)) {
                    $io->warning('No events to notify!');
                } else {
                    $io->success(sprintf('Found %d events. Starting notifications', count(($weeklyEvents))));
                }
                $this->eventNotifier->notifyWeekly($weeklyEvents);
            }

            if ($daily) {
                $io->section('Starting daily notifications');
                $dailyEvents = $events->filterDaily();
                $this->eventNotifier->notifyDaily($dailyEvents);
                if (0 === count($dailyEvents)) {
                    $io->warning('No events to notify!');
                } else {
                    $io->success(sprintf('Found %d events. Starting notifications', count(($dailyEvents))));
                }
            }

            if ($upcoming) {
                $io->section('Starting upcoming notifications');
                $upcomingEvents = $events->filterUpcoming();
                $this->eventNotifier->notifyUpcoming($upcomingEvents);
                if (0 === count($upcomingEvents)) {
                    $io->warning('No events to notify!');
                } else {
                    $io->success(sprintf('Found %d events. Starting notifications', count(($upcomingEvents))));
                }
            }
        } catch (EventNotifierException $eventNotifierException) {
            $this->logger->error(
                $eventNotifierException->getChannel(),
                ['message' => $eventNotifierException->getMessage()]
            );
        }

        $io->success('Program completed!');

        return 0;
    }

    private function checkValidOptions(array $options): ?InvalidOptionException
    {
        if (!$options['month'] && !$options['weekly'] && !$options['daily'] && !$options['upcoming']) {
            throw new InvalidOptionException('This command needs at least an option');
        }

        return null;
    }
}
