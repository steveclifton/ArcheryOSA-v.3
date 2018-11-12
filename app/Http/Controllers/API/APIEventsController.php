<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Events\PublicEvents\EventResultsController;
use App\Models\Division;
use App\Models\Event;
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


    public function getEventResults(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Event not found'
                ]
            ]);
        }

        $eventresultscontroller = new EventResultsController();

        $return = [];
        $return['success'] = true;
        $return['data'] = [];
        // find out what the event type is
        switch ($event->eventtypeid) {

            // Event
            case 1:

                if (empty($request->competitionid) || $request->competitionid == 'overall') {
                    // overall
                    $data = $eventresultscontroller->getEventOverallResults($event, true);
                    $return['data']['results'] = !empty($data['finalResults']) ? $data['finalResults'] : [];
                }
                else {
                    // particular competition
                }

                break;


            // League
            case 2:

                if (empty($request->competitionid)) {
                    // overall
                }
                else {
                    // particular week
                }

                break;

            // Shouldnt get here, yet..
            default:

                return null;

        }

        return response()->json($return);

    }
}
