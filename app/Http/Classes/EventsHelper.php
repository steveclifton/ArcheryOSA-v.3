<?php

namespace App\Http\Classes;

use App\Models\Competition;
use App\Models\Division;
use App\Models\EventCompetition;
use Illuminate\Support\Facades\DB;

class EventsHelper
{
    public function __construct()
    {

    }


    public function getEventsDateRange($event)
    {
        if (!is_object($event)) {
            return false;
        }

        $start = new \DateTime( $event->start );
        $end = new \DateTime( $event->end );
        $end = $end->modify( '+1 day' );
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($start, $interval ,$end);

        return $daterange;
    }

    public function getMappedCompetitionTree()
    {
        // Get all available competitions
        $competitions = DB::select("
            SELECT c.*, o.label as orgname
            FROM `competitions` c
            JOIN `organisations` o USING (`organisationid`)
            WHERE c.visible = 1
        ");

        $mappedcompetitions = [];
        foreach ($competitions as $competition) {
            $orgname = !empty($competition->orgname) ? $competition->orgname : 'Other';

            $roundtype = 'Outdoor';
            if ($competition->type == 'i') {
                $roundtype = 'Indoor';
            }
            else if ($competition->type == 'f') {
                $roundtype = 'Field';
            }
            else if ($competition->type == 'c') {
                $roundtype = 'Clout';
            }
            $mappedcompetitions[$orgname][$roundtype][] = $competition;
        }

        return $mappedcompetitions;
    }

    public function getMappedDivisionsTree()
    {
        // Get all available competitions
        $divisions = Division::get();

        $mappeddivisions = [];
        foreach ($divisions as $division) {
            $bowtype = 'other';

            switch (1) {
                case (stripos($division->label, 'compound') > 0) :
                    $bowtype = 'compound';
                    break;
                case (stripos($division->label, 'recurve') > 0) :
                    $bowtype = 'recurve';
                    break;
                case (stripos($division->label, 'barebow') > 0) :
                    $bowtype = 'barebow';
                    break;
                case (stripos($division->label, 'longbow') > 0) :
                    $bowtype = 'longbow';
                    break;
                case (stripos($division->label, 'crossbow') > 0) :
                    $bowtype = 'crossbow';
                    break;

            }

            $mappeddivisions[$bowtype][] = $division;
        }

        return $mappeddivisions;
    }

    public function getEventCompetitionLabels()
    {

    }

    public function getCompetitionLabels($eventid)
    {
        $eventcompetitions = EventCompetition::where('eventid', $eventid ?? NULL)->get();

        if (empty($eventcompetitions)) {
            return '';
        }

        $complabels = [];
        foreach ($eventcompetitions as $eventcompetition) {
            $competitions = Competition::wherein('competitionid', json_decode($eventcompetition->competitionids))->get();
            foreach ($competitions as $comp) {
                $complabels[$comp->competitionid] = $comp->label;
            }
        }

        return $complabels;

    }

    public function getEventDivisions($eventcompetitions)
    {

        if (empty($eventcompetitions)) {
            return false;
        }

        $returnarr = [];

        foreach ($eventcompetitions as $comp){
            $divisions = Division::wherein('divisionid', json_decode($comp->divisionids))->get();

            foreach ($divisions as $division) {
                $returnarr[$division->divisionid] = $division->label;
            }
        }

        return $returnarr;


    }


    public function getEventCompetitions($eventcompetitions)
    {

        if (empty($eventcompetitions)) {
            return false;
        }

        $returnarr = [];

        foreach ($eventcompetitions as $comp){
            $competitions = Competition::wherein('competitionid', json_decode($comp->competitionids))->get();

            foreach ($competitions as $competition) {
                $returnarr[$competition->competitionid] = $competition->label;
            }
        }

        return $returnarr;


    }
}