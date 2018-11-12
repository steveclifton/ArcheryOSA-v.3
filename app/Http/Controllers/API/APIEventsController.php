<?php

namespace App\Http\Controllers\API;

use App\Models\Division;
use App\Models\EventCompetition;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class APIEventsController extends Controller
{

    /**
     * Gets all upcoming events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUpcomingEvents()
    {
        $events = DB::select("
            SELECT e.eventid, e.label as eventname, e.eventtypeid, e.organisationid, e.clubid, e.entryclose, e.start, e.end, e.daycount, 
             e.contactname, e.phone, e.email, e.location, e.cost, e.bankaccount, e.bankreference, e.schedule, e.info,
              e.eventurl, e.organisationid, e.entrylimit,  es.label as eventstatus, c.label as clubname
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            LEFT JOIN `clubs` c ON (e.`clubid` = c.`clubid`)
            WHERE `e`.`end` > NOW()
            AND `e`.`visible` = 1
            ORDER BY `e`.`start` 
        ");

        foreach ($events as $event) {
            $event->eventurl = route('event', $event->eventurl);

            $event->competitions = DB::select("
                SELECT `eventcompetitionid`,`date`,`label` as eventcompetitionname,`eventid`,`roundids`,
                        `currentweek`,`location`,`schedule`,`divisionids`
                FROM `eventcompetitions`
                WHERE `eventid` = :eventid
            ", ['eventid' => $event->eventid]);

            if (!empty($event->competitions)) {
                foreach ($event->competitions as $competition) {

                    $divisionids = json_decode($competition->divisionids);
                    $divisionids = implode(',', (array) $divisionids);
                    $competition->divisons = DB::select("
                        SELECT `divisionid`,`label`,`code`
                        FROM `divisions`
                        WHERE `divisionid` IN (". $divisionids .") 
                    ");
                    $competition->divisionids = null;

                    $roundids = json_decode($competition->roundids);
                    $roundids = implode(',', (array) $roundids);
                    $competition->rounds = DB::select("
                        SELECT *
                        FROM `rounds`
                        WHERE `roundid` IN (". $roundids .")
                    ");
                    $competition->roundids = null;
                }

            }

        }

        return response()->json([
            'success' => true,
            'data' => [
                'events' => $events
            ]
        ]);
    }


    /**
     * Gets all previous events
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPreviousEvents()
    {
        $events = DB::select("
            SELECT e.eventid, e.label as eventname, e.eventtypeid, e.organisationid, e.clubid, e.entryclose, e.start, e.end, e.daycount, 
              e.contactname, e.phone, e.email, e.location, e.cost, e.bankaccount, e.bankreference, e.schedule, e.info,
              e.eventurl, e.organisationid, e.entrylimit,  es.label as eventstatus, c.label as clubname
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            LEFT JOIN `clubs` c ON (e.`clubid` = c.`clubid`)
            WHERE `e`.`end` < NOW()
            AND `e`.`visible` = 1
            ORDER BY `e`.`start` 
        ");


        foreach ($events as $event) {
            $event->eventurl = route('event', $event->eventurl);

            $event->competitions = DB::select("
                SELECT `eventcompetitionid`,`date`,`label` as eventcompetitionname,`eventid`,`roundids`,
                        `currentweek`,`location`,`schedule`,`divisionids`
                FROM `eventcompetitions`
                WHERE `eventid` = :eventid
            ", ['eventid' => $event->eventid]);

            if (!empty($event->competitions)) {
                foreach ($event->competitions as $competition) {

                    $divisionids = json_decode($competition->divisionids);
                    $divisionids = implode(',', (array) $divisionids);
                    $competition->divisons = DB::select("
                        SELECT `divisionid`,`label`,`code`
                        FROM `divisions`
                        WHERE `divisionid` IN (". $divisionids .") 
                    ");
                    $competition->divisionids = null;

                    $roundids = json_decode($competition->roundids);
                    $roundids = implode(',', (array) $roundids);
                    $competition->rounds = DB::select("
                        SELECT *
                        FROM `rounds`
                        WHERE `roundid` IN (". $roundids .")
                    ");
                    $competition->roundids = null;
                }

            }

        }
        
        return response()->json([
            'success' => true,
            'data' => [
                'events' => $events
            ]
        ]);
    }
}
