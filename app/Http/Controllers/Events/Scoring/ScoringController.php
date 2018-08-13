<?php

namespace App\Http\Controllers\Events\Scoring;

use App\Http\Classes\EventsHelper;
use App\Models\Competition;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\EventEntry;
use App\Models\Round;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScoringController extends Controller
{
    public function __construct(Request $request)
    {

        // check to see if they are an event admin and can score
        $this->helper = new EventsHelper();
        $this->event  = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($this->event)) {
            return redirect('/');
        }
        $eventAdmin = EventAdmin::where('eventid', $this->event->eventid)
                                ->where('userid', Auth::id())
                                ->where('canscore', 1)
                                ->get()
                                ->first();

        if (empty($eventAdmin)) {
            return redirect('/');
        }

    }




    public function getEventScoringList()
    {
        $event = $this->event;

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->orderBy('date', 'asc')->get();
        $competitions = [];
        foreach ($eventcompetitions as $eventcompetition) {
            $comps = Competition::wherein('competitionid', json_decode($eventcompetition->competitionids))->get();

            foreach ($comps as $comp) {
                $data = new \stdClass;
                $data->eventcompetitionid = $eventcompetition->eventcompetitionid;
                $data->competitionid = $comp->competitionid;
                $data->date = $eventcompetition->date;
                $data->label = $eventcompetition->label . " (" . $comp->label . ")";
                $competitions[] = $data;
            }
        }

        return view('events.scoring.event-scoring-list', compact('event', 'competitions'));
    }







    public function getEventScoringView(Request $request)
    {
        $event = $this->event;

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)
                                            ->where('eventcompetitionid', $request->eventcompetitionid ?? -1)
                                            ->get()
                                            ->first();

        $competition = Competition::where('competitionid', $request->competitionid)
                                ->get()
                                ->first();


        if (empty($eventcompetition) || empty($competition)) {
            return back()->with('failure', 'Invalid, please try again');
        }


        // Event Entries
        $entrys = DB::select("
            SELECT ee.*, ec.entrycompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4, r.unit
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ec`.`competitionid` = :competitionid
            AND `ee`.`entrystatusid` = 2
        ", ['eventcompetitionid' => $eventcompetition->eventcompetitionid,
            'competitionid' => $competition->competitionid
        ]);

        $evententrys = [];
        $roundids = [];
        foreach ($entrys as $entry) {

            $scores = Score::where('entryid', $entry->entryid)
                            ->where('roundid', $entry->roundid)
                            ->get();


            $entry->total = DB::table('scores')
                            ->where('entryid', $entry->entryid)
                            ->where('roundid', $entry->roundid)
                            ->sum('score');

            $entry->inners = DB::table('scores')
                            ->where('entryid', $entry->entryid)
                            ->where('roundid', $entry->roundid)
                            ->sum('inners');
            $entry->max   = DB::table('scores')
                            ->where('entryid', $entry->entryid)
                            ->where('roundid', $entry->roundid)
                            ->sum('max');

            $i = 1;
            foreach ($scores as $score) {
                $label = 'score' . $i++;
                $entry->{$label} = $score;
            }

            $evententrys[$entry->bowtype][$entry->divisionname][] = $entry;
            $roundids[] = $entry->roundid;

        }

//        dd($evententrys);

        return view('events.scoring.event-scoring', compact('event', 'evententrys'));
    }





    /********************
     * POST
     *******************/


    public function postScores(Request $request) {


        $event = $this->event;



        if (empty($event) || empty($request->data) || !is_array($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        foreach ($request->data as $result) {

            // get the entry
            $evententry = EventEntry::where('entryid', $result['entryid']??-1)
                                    ->where('eventid', $event->eventid)
                                    ->get()
                                    ->first();

            $entrycompetition = EntryCompetition::where('entrycompetitionid', $result['entrycompetitionid']??-1)
                                                ->where('eventid', $event->eventid)
                                                ->where('userid', $evententry->userid)
                                                ->get()
                                                ->first();

            if (empty($evententry) || empty($entrycompetition)) {
                continue;
            }


            // check if any scores exist, if none, create, else update
            $scores = Score::where('entryid', $evententry->entryid)
                            ->where('eventid', $event->eventid)
                            ->where('roundid', $entrycompetition->roundid)
                            ->get()
                            ->first();



            if (empty($scores)) {
                // create
                foreach ($result['score'] ?? [] as $data) {



                    $score = new Score();
                    $score->entryid            = $evententry->entryid;
                    $score->entrycompetitionid = $entrycompetition->entrycompetitionid;
                    $score->userid             = $evententry->userid;
                    $score->roundid            = $entrycompetition->roundid;
                    $score->eventid            = $event->eventid;
                    $score->eventcompetitionid = $entrycompetition->eventcompetitionid;
                    $score->divisionid         = $evententry->divisionid;
                    $score->distance           = $data['distance'] ?? '';
                    //$score->unit               = 1;
                    $score->score              = $data['score'] ?? 0;
                    $score->hits               = $data['hits'] ?? 0;
                    $score->max                = $data['max'] ?? 0;
                    $score->inners             = $data['inners'] ?? 0;
                    $score->save();
                }

            }
            else {
                foreach ($result['score'] ?? [] as $data) {


                    $score = Score::where('scoreid', $data['scoreid'])
                                    ->where('entryid', $evententry->entryid)
                                    ->where('userid', $evententry->userid)
                                    ->get()
                                    ->first();

                    // shouldnt ever be null
                    if (empty($score)) {
                        continue;
                    }

                    $score->distance           = $data['distance'] ?? '';
                    //$score->unit               = 1;
                    $score->score              = $data['score'] ?? 0;
                    $score->hits               = $data['hits'] ?? 0;
                    $score->max                = $data['max'] ?? 0;
                    $score->inners             = $data['inners'] ?? 0;
                    $score->save();
                }
            }

        }

        return response()->json([
            'success' => true,
            'message' => 'Scores entered successfully'
        ]);
    }
}
