<?php

namespace App\Command;

use App\Client\GoogleCalendarClient;
use Contao\CalendarEventsModel;
use Contao\CoreBundle\DependencyInjection\Attribute\AsCronJob;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCronJob('hourly')]
#[AsCommand(name: 'ffwenns:import:events', description: 'Import google calendar events')]
class ImportEventsCommand extends Command
{

    public function __construct(private readonly ContaoFramework $framework, private GoogleCalendarClient $calendar)
    {
        $this->framework->initialize();

        parent::__construct();
    }

    public function __invoke(): void
    {
        $this->execute();
    }

    protected function execute(InputInterface $input = null, OutputInterface $output = null): int
    {
        $events = $this->calendar->getEvents();

        foreach ($events as $event) {
            $uid = explode('@', $event->uid)[0];

            if (CalendarEventsModel::findByAlias($uid)) {
                continue;
            }

            $newEvent = new CalendarEventsModel();
            $newEvent->pid = 1;
            $newEvent->author = 1;
            $newEvent->alias = $uid;
            $newEvent->title = $event->summary;
            $newEvent->teaser = $event->description;
            $newEvent->addTime = true;
            $newEvent->startDate = strtotime($event->dtstart);
            $newEvent->startTime = strtotime($event->dtstart);
            $newEvent->endDate = strtotime($event->dtend);
            $newEvent->endTime = strtotime($event->dtend);
            $newEvent->published = true;
            $newEvent->tstamp = time();
            $newEvent->save();
        }

        return Command::SUCCESS;
    }
}