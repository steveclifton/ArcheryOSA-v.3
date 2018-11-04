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

            // get users entry competition
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


            // push scores into an array, save only if total is not empty
            $saveScore      = false;
            $scoresArray    = [];

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

                    if ($data['key'] == 'total' && !empty($data['score'])) {
                        $saveScore = true;
                    }

                    $scoresArray[] = $score;

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

                if ($saveScore) {
                    $flatscore->week = $week;
                    // Save the flat score
                    $flatscore->save();
                }

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
                        ->get()
                        ->first();
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

                    if (!empty($flatscore)) {
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

            // triggered only on created
            if ($saveScore) {
                foreach ($scoresArray as $score) {
                    $score->save();
                }
            }



        } // end of looping over each user

        return response()->json([
            'success' => true,
            'message' => 'Scores entered successfully'
        ]);
    }



    /********************
     * AJAX
     *******************/
    public function postLeagueScore(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data'    => 'Event not found'
            ]);
        }

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)
            ->get()
            ->first();

        // Event Entries
        $entrys = DB::select("
            SELECT ee.*, ec.divisionid as divisionid, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4,r.dist1max,r.dist2max,r.dist3max,r.dist4max,r.unit, ecomp.currentweek
            FROM `evententrys` ee
            JOIN `eventcompetitions` ecomp ON (ecomp.eventid = ee.eventid)
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = :eventid
            AND `ee`.`userid` = :scoringuserid
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            AND (ee.`userid` IN (
                  SELECT `relationid`
                  FROM `userrelations`
                  WHERE `userid` = '".Auth::id()."'
                )
                OR 
                ee.`userid` = '".Auth::id()."'
            )
            ORDER BY `d`.label, ee.firstname
        ", ['eventid'=> $event->eventid,
                'eventcompetitionid' => $eventcompetition->eventcompetitionid,
                'scoringuserid'=> $request->userid ]
        );

        $entry = null;
        foreach($entrys as $e) {
            if ($e->divisionid == $request->divisionid) {
                $entry = $e;
                break;
            }
        }

        if (empty($entry)) {
            return response()->json([
                'success' => false,
                'data'    => 'Cannot score for this archer'
            ]);
        }

        // check if any scores exist, if none, create, else update
        $flatscore = FlatScore::where('entryid', $entry->entryid)
            ->where('entrycompetitionid', $entry->entrycompetitionid)
            ->where('week', $entry->currentweek)
            ->where('roundid', $entry->roundid)
            ->where('divisionid', $entry->divisionid)
            ->get()
            ->first();


        if (empty($flatscore)) {
            // create

            $flatscore = new FlatScore();
            $flatscore->entryid = $entry->entryid;
            $flatscore->entrycompetitionid = $entry->entrycompetitionid;
            $flatscore->userid = $entry->userid;
            $flatscore->roundid = $entry->roundid;
            $flatscore->eventid = $entry->eventid;
            $flatscore->divisionid = $entry->divisionid;
            $flatscore->unit = $entry->unit;
            $flatscore->week = $entry->currentweek;

            $flatscore->totalhits = intval($request->totalhit);
            $flatscore->inners    = intval($request->total10);
            $flatscore->max       = intval($request->totalx);
            $flatscore->total     = 0;

            foreach (range(1,4) as $i) {
                $dist = "dist" . $i;
                $distscore = "dist" . $i++ . 'score';

                // Flat Scores
                $flatscore->{$dist} = $entry->{$dist}; // distance from the entry
                $flatscore->{$distscore} = intval($request->{$dist});
                $flatscore->total += intval($request->{$dist});

                if (empty($request->{$dist})) {
                    continue;
                }

                // Scores
                $score = new Score();
                $score->entryid = $entry->entryid;
                $score->entrycompetitionid = $entry->entrycompetitionid;
                $score->userid = $entry->userid;
                $score->roundid = $entry->roundid;
                $score->eventid = $entry->eventid;
                $score->eventcompetitionid = $entry->eventcompetitionid;
                $score->divisionid = $entry->divisionid;
                $score->key    = intval($entry->{$dist});
                $score->score  = intval($request->{$dist});
                $score->hits   = intval($request->totalhits);
                $score->max    = intval($request->totalx);
                $score->inners = intval($request->total10);
                $score->week   = $entry->currentweek;

                $score->save();

            }
            $flatscore->save();

            // create scores for the totals
            foreach(['total', 'max', 'inners'] as $key) {
                // Scores
                $score = new Score();
                $score->entryid = $entry->entryid;
                $score->entrycompetitionid = $entry->entrycompetitionid;
                $score->userid = $entry->userid;
                $score->roundid = $entry->roundid;
                $score->eventid = $entry->eventid;
                $score->eventcompetitionid = $entry->eventcompetitionid;
                $score->divisionid = $entry->divisionid;
                $score->key    = $key;
                $score->week   = $entry->currentweek;

                switch ($key) {
                    case 'total' :
                        $score->score  = intval($flatscore->total);
                    break;

                    case 'max' :
                        $score->score  = intval($request->totalx);
                    break;

                    case 'inners' :
                        $score->score  = intval($request->total10);
                    break;
                }
                $score->save();
            }

        }
        else {
            // update
            $flatscore->total = 0;
            foreach(range(1,4) as $i) {

                $dist = "dist" . $i;
                $distscore = "dist" . $i++ . 'score';

                // Flat Scores
                $flatscore->{$dist} = $entry->{$dist}; // distance from the entry
                $flatscore->{$distscore} = intval($request->{$dist});
                $flatscore->total += intval($request->{$dist});

                if (empty($request->{$dist})) {
                    continue;
                }

                $score = Score::where('entryid', $entry->entryid)
                                ->where('entrycompetitionid',$entry->entrycompetitionid)
                                ->where('userid', $entry->userid)
                                ->where('roundid', $entry->roundid)
                                ->where('divisionid', $entry->divisionid)
                                ->where('week', $entry->currentweek)
                                ->get()
                                ->first();

                if (empty($score)) {
                    // raise error
                    continue;
                }


                $score->score  = intval($request->{$dist});
                $score->hits   = intval($request->totalhits);
                $score->max    = intval($request->totalx);
                $score->inners = intval($request->total10);
                $score->save();

            }

            $flatscore->totalhits = intval($request->totalhit);
            $flatscore->inners    = intval($request->total10);
            $flatscore->max       = intval($request->totalx);
            $flatscore->save();

            foreach(['total', 'max', 'inners'] as $key) {
                // Scores
                $score = Score::where('entryid', $entry->entryid)
                                ->where('entrycompetitionid',$entry->entrycompetitionid)
                                ->where('userid', $entry->userid)
                                ->where('roundid', $entry->roundid)
                                ->where('divisionid', $entry->divisionid)
                                ->where('week', $entry->currentweek)
                                ->where('key', $key)
                                ->get()
                                ->first();
                switch ($key) {
                    case 'total' :
                        $score->score  = intval($flatscore->total);
                        break;

                    case 'max' :
                        $score->score  = intval($request->totalx);
                        break;

                    case 'inners' :
                        $score->score  = intval($request->total10);
                        break;
                }
                $score->save();
            }

        }

        return response()->json([
            'success' => true,
            'data'    => 'Scoring successful!'
        ]);

    }

}
