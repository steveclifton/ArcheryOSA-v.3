<?php

namespace App\Http\Classes\Competitions;

use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Support\Facades\DB;

class Template
{

    protected Event $event;
    protected $dateRange = [];

    const TEMPLATES = [
        '1d2x720' => 'Day 1: Double 720',
        '2d2x7201dmp' => 'Day 1: Double 720, Day 2: Matchplay',
        '2d2x720' => 'Day 1: Double 720, Day 2: Double 720',
    ];

    public function __construct(Event $event)
    {
        $this->event = $event;
        $this->dateRange = $event->getEventsDateRange();
    }

    public function create(string $template)
    {
        switch ($template) {
            case '1d2x720':
                // Day 1: Double 720
                $dates = $this->getEventDates();
                $day1 = isset($dates[0]) ? $dates[0] : date('Y-m-d');

                $this->create720('Morning', 1, $day1);
                $this->create720('Afternoon', 2, $day1);
                break;

            case '2d2x7201dmp':
                // Day 1: Double 720, Day 2: Matchplay
                $dates = $this->getEventDates();
                $day1 = isset($dates[0]) ? $dates[0] : date('Y-m-d');
                $day2 = isset($dates[1]) ? $dates[1] : $day1;

                $this->create720('Morning', 1, $day1);
                $this->create720('Afternoon', 2, $day1);

                $this->createMatchplay('Matchplay', 3, $day2);
                break;

            case '2d2x720':
                // Day 1: Double 720, Day 2: Double 720
                $dates = $this->getEventDates();

                $day1 = isset($dates[0]) ? $dates[0] : date('Y-m-d');
                $day2 = isset($dates[1]) ? $dates[1] : $day1;

                $this->create720('Morning', 1, $day1);
                $this->create720('Afternoon', 2, $day1);
                $this->create720('Morning', 3, $day2);
                $this->create720('Afternoon', 4, $day2);

                break;

            default:
                break;
        }

        return null;
    }

    protected function getEventDates()
    {
        $dates = [];
        foreach ($this->dateRange as $date) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }


    protected function create720(string $competitionName, int $sequence, string $date)
    {
        $roundIds = $this->getAnz720RoundIds();
        $divisionIds = $this->getAnzDivisionIds();

        $eventcompetition = new EventCompetition();
        $eventcompetition->eventid          = $this->event->eventid;
        $eventcompetition->label            = $competitionName;
        $eventcompetition->date             = $date;
        $eventcompetition->sequence         = $sequence;
        $eventcompetition->roundids         = json_encode($roundIds);
        $eventcompetition->divisionids      = json_encode($divisionIds);
        $eventcompetition->scoringlevel     = 1;
        $eventcompetition->scoringenabled   = 0;
        $eventcompetition->visible          = 1;
        $eventcompetition->currentweek      = 1;
        $eventcompetition->save();

        return true;
    }

    protected function createMatchplay(string $competitionName, int $sequence, string $date)
    {
        $roundId = [24];
        $divisionIds = $this->getAnzDivisionIds();

        $eventcompetition = new EventCompetition();
        $eventcompetition->eventid          = $this->event->eventid;
        $eventcompetition->label            = $competitionName;
        $eventcompetition->date             = $date;
        $eventcompetition->sequence         = $sequence;
        $eventcompetition->roundids         = json_encode($roundId);
        $eventcompetition->divisionids      = json_encode($divisionIds);
        $eventcompetition->scoringlevel     = 1;
        $eventcompetition->scoringenabled   = 0;
        $eventcompetition->visible          = 1;
        $eventcompetition->currentweek      = 1;
        $eventcompetition->save();

        return true;
    }

    protected function getAnz720RoundIds() : array
    {
        if (!empty($this->roundIds720)) {
            return $this->roundIds720;
        }

        $this->roundIds720 = DB::select("
            SELECT `roundid`
            FROM `rounds`
            WHERE `code` LIKE 'anz720-%'
        ");

        return $this->roundIds720 = array_column($this->roundIds720, 'roundid');
    }

    protected function getAnzDivisionIds() : array
    {
        if (!empty($this->anzDivisionIds)) {
            return $this->anzDivisionIds;
        }


        $this->anzDivisionIds = DB::select("
            SELECT `divisionid`
            FROM `divisions`
            WHERE `organisationid` = 1
        ");

        return $this->anzDivisionIds = array_column($this->anzDivisionIds, 'divisionid');
    }

}