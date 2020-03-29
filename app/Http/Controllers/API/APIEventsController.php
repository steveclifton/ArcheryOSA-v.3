<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Events\PublicEvents\Event\EventResultsController;
use App\Http\Controllers\Events\PublicEvents\League\LeagueResultsController;
use App\Http\Controllers\Events\PublicEvents\ResultsController;
use App\Models\Event;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class APIEventsController extends Controller
{

    private $eventFields = [
        'eventid', 'label', 'eventtypeid', 'organisationid',
        'clubid', 'entryclose', 'start', 'end', 'daycount', 'contactname',
        'phone' , 'email' , 'location' , 'cost' , 'bankaccount',
        'bankreference' , 'schedule' , 'info','eventurl', 'entrylimit'
    ];

    private $eventRenameFields = [
        'label' => 'eventname'
    ];

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
            $event->eventurlfull = route('event', $event->eventurl);

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
            $event->eventurlfull = route('event', $event->eventurl);

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


    public function getEventTypes()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'types' => EventType::get()
            ]
        ]);
    }



    public function getEventResults(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get($this->eventFields)->first();

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data' => [
                    'message' => 'Event not found'
                ]
            ]);
        }

        foreach ($this->eventRenameFields as $oldname => $newname) {
            if (isset($event->{$oldname})) {
                $event->{$newname} = $event->{$oldname};
                unset($event->{$oldname});
            }
        }

        $eventresultscontroller = new ResultsController();

        $return = [];
        $return['success'] = true;
        $return['data'] = [];
        $return['data']['event'] = $event;
        $return['data']['eventtype'] = EventType::where('eventtypeid', $event->eventtypeid)->pluck('label')->first();

        // find out what the event type is
        switch ($event->eventtypeid) {
            // Event
            // Postal
            case 1:
            case 3:

                if (empty($request->competitionid) || $request->competitionid == 'overall') {
                    // overall
                    $data = (new EventResultsController())->getOverallResults($event, true);

                    $return['data']['results'] = !empty($data['results']) ? $data['results'] : [];
                    $return['data']['competitions'] = !empty($data['competitionlabels']) ? $data['competitionlabels'] : [];
                }
                else {

                    // particular competition
                    $id = intval($request->competitionid);

                    if (!empty($id)) {
                        $data = (new EventResultsController())->getEventCompetitionResults($event, $id, true);
                        $return['data']['results'] = !empty($data['results']) ? $data['results'] : [];
                        $return['data']['competitions'] = !empty($data['eventcompetition']) ? $data['eventcompetition'] : [];
                    }

                }

                break;


            // League
            case 2:

                if (empty($request->competitionid)) {
                    // overall
                    $data = (new LeagueResultsController())->getLeagueOverallResults($event, true);
                    $return['data']['results'] = !empty($data['evententrys']) ? $data['evententrys'] : [];
                }
                else {
                    // particular week
                    $week = intval($request->competitionid);

                    if (!empty($week)) {
                        $data = (new LeagueResultsController())->getLeagueCompetitionResults($event, $week, true);
                        $return['data']['results'] = !empty($data['evententrys']) ? $data['evententrys'] : [];
                    }
                }

                break;

            // Shouldnt get here, yet..
            default:

                return null;

        }

        return response()->json($return);

    }
}
