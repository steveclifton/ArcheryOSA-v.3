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
        $this->event  = Event::where('eventurl', $request->eventurl)->first();

        if (empty($this->event)) {
            return redirect('/');
        }
        $eventAdmin = EventAdmin::where('eventid', $this->event->eventid)
                                ->where('userid', Auth::id())
                                ->where('canscore', 1)
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
                                            ->first();

        if (empty($eventcompetition)) {
            return back()->with('failure', 'Invalid, please try again');
        }

        // Event Entries
        $entries = DB::select("
            SELECT ee.*, ec.divisionid as divisionid, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4,r.dist1max,r.dist2max,r.dist3max,r.dist4max,r.unit
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = :eventid
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label, ee.firstname
        ", ['eventcompetitionid' => $eventcompetition->eventcompetitionid,
            'eventid' => $event->eventid
        ]);

        $currentWeek = $event->isLeague() ? $eventcompetition->currentweek : 0;

        $flatScores = FlatScore::where('eventcompetitionid', $eventcompetition->eventcompetitionid)->where('week', $currentWeek)->get()->toArray();

        $sortedFlatScores = [];
        array_walk($flatScores, function ($flatScore) use (&$sortedFlatScores) {
           $sortedFlatScores[$flatScore['entryid']][] = (object) $flatScore;
        });

        $evententrys = [];
        foreach ($entries as $entry) {

            $entryFlatScores = ($sortedFlatScores[$entry->entryid] ?? []);

            foreach ($entryFlatScores as $flatScore) {

                // Make sure the roundid and the divisionid match
                if ($flatScore->roundid != $entry->roundid || $flatScore->divisionid != $entry->divisionid) {
                    continue;
                }

                $entry->fsid = $flatScore->flatscoreid;

                // Loop over 4 possible distances for score
                foreach ([1,2,3,4] as $i) {
                    $label = 'dist' . $i;
                    $entry->{$label . 'score'} = $flatScore->{($label . 'score')};
                    $entry->{$label . 'hitsscore'} = $flatScore->{($label . 'hits')};
                    $entry->{$label . 'maxscore'} = $flatScore->{($label . 'max')};
                    $entry->{$label . 'innersscore'} = $flatScore->{($label . 'inners')};

                    $i++;
                }

                $entry->total = $flatScore->total;
                $entry->max = $flatScore->max;
                $entry->inners = $flatScore->inners;
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

        $requestScores = json_decode($request->data);

        if (empty($event) || !is_array($requestScores)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        foreach ($requestScores as $result) {

            // get the entry
            $evententry = EventEntry::where('hash', ($result->entryhash ?? -1) )
                                    ->where('eventid', $event->eventid)
                                    ->first();

            // get users entry competition
            $entrycompetition = EntryCompetition::where('entrycompetitionid', ($result->entrycompetitionid ?? -1))
                                                ->where('eventid', $event->eventid)
                                                ->where('userid', $evententry->userid ?? -1)
                                                ->first();

            if (empty($evententry) || empty($entrycompetition)) {
                continue;
            }

            $round = Round::where('roundid', $entrycompetition->roundid)->first();

            $flatScore = null;
            if (!empty($result->fsid)) {
                $flatScore = FlatScore::where('flatscoreid', $result->fsid)
                    ->first();
            }

            if (!$flatScore) {
                $flatScore = new FlatScore();

                $flatScore->entryid = $evententry->entryid;
                $flatScore->entrycompetitionid = $entrycompetition->entrycompetitionid;
                $flatScore->userid = $evententry->userid;
                $flatScore->roundid = $entrycompetition->roundid;
                $flatScore->eventid = $event->eventid;
                $flatScore->eventcompetitionid = $entrycompetition->eventcompetitionid;
                $flatScore->divisionid = $entrycompetition->divisionid;
                $flatScore->week = $week;
            }

            foreach ([1,2,3,4] as $i) {
                $label = 'dist' . $i++;
                $flatScore->{$label} = $round->{$label};
            }

            // Scores
            if (isset($result->dist1score)) {
                $flatScore->dist1score = $result->dist1score;
            }
            if (isset($result->dist2score)) {
                $flatScore->dist2score = $result->dist2score;
            }
            if (isset($result->dist3score)) {
                $flatScore->dist3score = $result->dist3score;
            }
            if (isset($result->dist4score)) {
                $flatScore->dist4score = $result->dist4score;
            }

            // Hits
            if (isset($result->dist1hits)) {
                $flatScore->dist1hits = $result->dist1hits;
            }
            if (isset($result->dist2hits)) {
                $flatScore->dist2hits = $result->dist2hits;
            }
            if (isset($result->dist3hits)) {
                $flatScore->dist3hits = $result->dist3hits;
            }
            if (isset($result->dist4hits)) {
                $flatScore->dist4hits = $result->dist4hits;
            }

            // Max
            if (isset($result->dist1max)) {
                $flatScore->dist1max = $result->dist1max;
            }
            if (isset($result->dist2max)) {
                $flatScore->dist2max = $result->dist2max;
            }
            if (isset($result->dist3max)) {
                $flatScore->dist3max = $result->dist3max;
            }
            if (isset($result->dist4max)) {
                $flatScore->dist4max = $result->dist4max;
            }

            // Inners
            if (isset($result->dist1inners)) {
                $flatScore->dist1inners = $result->dist1inners;
            }
            if (isset($result->dist2inners)) {
                $flatScore->dist2inners = $result->dist2inners;
            }
            if (isset($result->dist3inners)) {
                $flatScore->dist3inners = $result->dist3inners;
            }
            if (isset($result->dist4inners)) {
                $flatScore->dist4inners = $result->dist4inners;
            }

            // Result Total
            if (isset($result->total)) {
                $flatScore->total = $result->total;
            }

            // Result Inners
            if (isset($result->inners)) {
                $flatScore->inners = $result->inners;
            }

            // Results Max
            if (isset($result->max)) {
                $flatScore->max = $result->max;
            }

            $flatScore->save();

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
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'data'    => 'Event not found'
            ]);
        }

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)
                                            ->first();


        // Event Entries
        $entries = DB::select("
            SELECT ee.*, ec.divisionid as divisionid, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4,r.dist1max,r.dist2max,r.dist3max,r.dist4max,r.unit, IFNULL(ecomp.currentweek, 1) as currentweek
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
                  WHERE `userid` = :userrelationid 
                )
                OR 
                ee.`userid` = :userid
            )
            ORDER BY `d`.label, ee.firstname
        ", [
            'eventid'=> $event->eventid,
            'eventcompetitionid' => $eventcompetition->eventcompetitionid,
            'scoringuserid'=> $request->userid,
            'userrelationid' => Auth::id(),
            'userid' => Auth::id()
            ]
        );

        $entry = null;
        foreach ($entries as $e) {
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
            $flatscore->eventcompetitionid = $eventcompetition->eventcompetitionid;

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

            }
            $flatscore->save();
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
            }

            $flatscore->totalhits = intval($request->totalhit);
            $flatscore->inners    = intval($request->total10);
            $flatscore->max       = intval($request->totalx);
            $flatscore->save();
        }

        return response()->json([
            'success' => true,
            'data'    => 'Scoring successful!'
        ]);

    }

}
