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
use App\Models\FlatScore;
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

        // League event, make the event competition date current week
        if ($event->eventtypeid == 2) {
            foreach($eventcompetitions as $eventcompetition) {
                $eventcompetition->date = 'Week ' . $eventcompetition->currentweek;
            }
        }

        return view('events.scoring.event-scoring-list', compact('event', 'eventcompetitions'));
    }







    public function getEventScoringView(Request $request)
    {
        $event = $this->event;

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)
                                            ->where('eventcompetitionid', $request->eventcompetitionid ?? -1)
                                            ->get()
                                            ->first();

        if (empty($eventcompetition)) {
            return back()->with('failure', 'Invalid, please try again');
        }

        // Event Entries
        $entrys = DB::select("
            SELECT ee.*, ec.divisionid as divisionid, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4,r.dist1max,r.dist2max,r.dist3max,r.dist4max,r.unit
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            
            ORDER BY `d`.label, ee.firstname
        ", ['eventcompetitionid' => $eventcompetition->eventcompetitionid
        ]);


        $currentWeek = $event->isLeague() ? $eventcompetition->currentweek : 0;
        $evententrys = [];
        foreach ($entrys as $entry) {

            $scores = Score::where('entryid', $entry->entryid)
                            ->where('roundid', $entry->roundid)
                            ->where('divisionid', $entry->divisionid)
                            ->where('eventcompetitionid', $entry->eventcompetitionid)
                            ->where('week', $currentWeek)
                            ->get();

            $i = 1;
            foreach ($scores as $score) {
                $label = 'score' . $i++;
                $entry->{$label} = $score;

                if ($score->key == 'total') {
                    $entry->total = $score;
                }
                else if ($score->key == 'max') {
                    $entry->max = $score;
                }
                else if ($score->key == 'inners') {
                    $entry->inners = $score;
                }
            }

            $gender = '';
            if (empty($eventcompetition->ignoregenders)) {
                $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            }

            $evententrys[$entry->bowtype][$gender . $entry->divisionname][$entry->roundid][] = $entry;
        }

        return view('events.scoring.event-scoring', compact('event', 'evententrys', 'eventcompetition'));
    }





    /********************
     * POST
     *******************/


    public function postScores(Request $request) {


        $event = $this->event;

        $week = 0;

        // league event, get the current week
        if ($event->eventtypeid == 2) {
            $week = EventCompetition::where('eventid', $event->eventid)->pluck('currentweek')->first();
        }

        if (empty($event) || empty($request->data) || !is_array($request->data)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        foreach ($request->data as $result) {

            // get the entry
            $evententry = EventEntry::where('hash', $result['entryhash']??-1)
                                    ->where('eventid', $event->eventid)
                                    ->get()
                                    ->first();


            $entrycompetition = EntryCompetition::where('entrycompetitionid', $result['entrycompetitionid'] ?? -1)
                                                ->where('eventid', $event->eventid)
                                                ->where('userid', $evententry->userid ?? -1)
                                                ->get()
                                                ->first();

            if (empty($evententry) || empty($entrycompetition)) {
                continue;
            }

            // check if any scores exist, if none, create, else update
            $scores = Score::where('entryid', $evententry->entryid)
                            ->where('entrycompetitionid', $entrycompetition->entrycompetitionid)
                            ->where('week', $week)
                            ->where('roundid', $entrycompetition->roundid)
                            ->where('divisionid', $entrycompetition->divisionid)
                            ->get()
                            ->first();


            if (empty($scores)) {
                // create

                $flatscore = new FlatScore();

                $i = 1;

                foreach ($result['score'] ?? [] as $data) {
                    $score = new Score();
                    $score->entryid = $evententry->entryid;
                    $score->entrycompetitionid = $entrycompetition->entrycompetitionid;
                    $score->userid = $evententry->userid;
                    $score->roundid = $entrycompetition->roundid;
                    $score->eventid = $event->eventid;
                    $score->eventcompetitionid = $entrycompetition->eventcompetitionid;
                    $score->divisionid = $entrycompetition->divisionid;
                    $score->key    = $data['key'] ?? '';
                    $score->score  = intval($data['score'] ?? 0);
                    $score->hits   = intval($data['hits'] ?? 0);
                    $score->max    = intval($data['max'] ?? 0);
                    $score->inners = intval($data['inners'] ?? 0);
                    $score->week   = $week;
                    $score->save();

                    if (is_numeric($data['key'])) {

                        // do the flat scores
                        $flatscore->entryid = $evententry->entryid;
                        $flatscore->entrycompetitionid = $entrycompetition->entrycompetitionid;
                        $flatscore->userid = $evententry->userid;
                        $flatscore->roundid = $entrycompetition->roundid;
                        $flatscore->eventid = $event->eventid;
                        $flatscore->eventcompetitionid = $entrycompetition->eventcompetitionid;
                        $flatscore->divisionid = $entrycompetition->divisionid;
                        // add score
                        $distscore = "dist" . $i . 'score';
                        $flatscore->{$distscore} = intval($data['score'] ?? 0);
                        // add distance
                        $distkey = "dist" . $i++;
                        $flatscore->{$distkey} = $data['key'] ?? '';
                    }


                    if ($data['key'] == 'total') {
                        $flatscore->total = intval($data['score'] ?? 0);
                    }
                    if ($data['key'] == 'max') {
                        if (is_null($flatscore->max)) {
                            $flatscore->max = 0;
                        }

                        $flatscore->max += intval($data['score'] ?? 0);
                    }
                    if ($data['key'] == 'inners') {
                        if (is_null($flatscore->inners)) {
                            $flatscore->inners = 0;
                        }

                        $flatscore->inners += intval($data['score'] ?? 0);
                    }


                }


                $flatscore->week = $week;
                // Save the flat score
                $flatscore->save();
            }
            else {
                // Update Score
                $i = 1;
                $flatscore = null;
                $inners = 0;
                $max    = 0;

                if (empty($flatscore)) {
                    $flatscore = FlatScore::where('entryid', $evententry->entryid)
                        ->where('entrycompetitionid', $entrycompetition->entrycompetitionid)
                        ->where('userid', $evententry->userid)
                        ->where('divisionid', $entrycompetition->divisionid)
                        ->where('week', $week)
                        ->get()->first();

                }

                foreach ($result['score'] ?? [] as $data) {

                    $score = Score::where('scoreid', $data['scoreid'])
                                    ->where('entryid', $evententry->entryid)
                                    ->where('entrycompetitionid', $entrycompetition->entrycompetitionid)
                                    ->where('userid', $evententry->userid)
                                    ->where('divisionid', $entrycompetition->divisionid)
                                    ->where('week', $week)
                                    ->get()
                                    ->first();

                    // shouldnt ever be null
                    if (empty($score)) {
                        continue;
                    }

                    $score->key    = $data['key'] ?? '';
                    $score->score  = intval($data['score'] ?? 0);
                    $score->hits   = intval($data['hits'] ?? 0);
                    $score->max    = intval($data['max'] ?? 0);
                    $score->inners = intval($data['inners'] ?? 0);
                    $score->save();

                    // flatscore update
                    if (is_numeric($data['key'])) {

                        // add score
                        $distscore = "dist" . $i . 'score';
                        $flatscore->{$distscore} = intval($data['score'] ?? 0);
                        // add distance
                        $distkey = "dist" . $i++;
                        $flatscore->{$distkey} = $data['key'] ?? '';
                    }

                    if ($data['key'] == 'total') {
                        $flatscore->total = intval($data['score'] ?? 0);
                    }

                    if ($data['key'] == 'max') {
                        $max += intval($data['score'] ?? 0);
                    }
                    if ($data['key'] == 'inners') {
                        $inners += intval($data['score'] ?? 0);
                    }

                }
                if (!empty($flatscore)) {
                    $flatscore->max    = $max;
                    $flatscore->inners = $inners;
                    $flatscore->save();
                }
            }

        }

        return response()->json([
            'success' => true,
            'message' => 'Scores entered successfully'
        ]);
    }

}
