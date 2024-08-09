<?php

namespace App\Client;

use ICal\ICal;

class GoogleCalendarClient
{
    private ICal $ical;

    public function __construct(private readonly string $calendarUrl)
    {
        $this->ical = new ICal($this->calendarUrl, [
            'defaultTimeZone' => 'Europe/Berlin',
            'filterDaysBefore' => 1,
            'filterDaysAfter' => 30
        ]);
    }

    public function getEvents(): array
    {
        return $this->ical->eventsFromInterval('1 month');
    }
}