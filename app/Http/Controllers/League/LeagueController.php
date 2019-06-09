<?php

namespace App\Http\Controllers\League;

use App\LeaguePoint;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\FlatScore;
use App\Models\Score;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LeagueController extends Controller
{



    public function getUserLeagueScoringView(Event $event)
    {

        if (empty($event)) {
            return redirect('/');
        }

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)
                                            ->first();

        // Event Entries
        $entrys = DB::select("
            SELECT ee.*, ec.divisionid as divisionid, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype,
                  r.dist1,r.dist2,r.dist3,r.dist4,r.dist1max,r.dist2max,r.dist3max,r.dist4max,r.unit, ecomp.currentweek, r.label as roundname
            FROM `evententrys` ee
            JOIN `eventcompetitions` ecomp ON (ecomp.eventid = ee.eventid)
            JOIN `entrycompetitions` ec ON (ee.`entryid` = ec.`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = :eventid
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
            'eventcompetitionid' => $eventcompetition->eventcompetitionid]
        );

        foreach ($entrys as $entry) {

            $week = !is_null($entry->currentweek) ? $entry->currentweek : 1;

            $entry->score = FlatScore::where('entryid', $entry->entryid)
                ->where('entrycompetitionid', $entry->entrycompetitionid)
                ->where('week', $week)
                ->where('roundid', $entry->roundid)
                ->where('divisionid', $entry->divisionid)
                ->first();

        }

        return view('events.scoring.public.league', compact('event', 'entrys'));

    }


    public function processLeagueResults(Request $request)
    {

        // Get the event

        $eventcompetition = EventCompetition::where('eventid', $event->eventid ?? -1)->get()->first();

        // Get the Event Round
        $eventround = DB::select("
            SELECT `totalmax`
            FROM `rounds` r
            JOIN `eventcompetitions` ec ON (r.`roundid` = ec.roundids)
            WHERE ec.eventid = :eventid
        ", ['eventid' => $event->eventid ?? -1]);

        $eventroundmax = $eventround[0]->totalmax ?? -1;


        // Get this weeks scores
        $scores = DB::select("
            SELECT *
            FROM `scores_flat`
            WHERE `week` = :week
            AND `eventid` = :eventid
            AND `total` <> 0
            
            ORDER BY `divisionid`, `total` DESC
        ", ['week' => $eventcompetition->currentweek, 'eventid' => $event->eventid]);

        // Get all the score averages for the comp
        $scoreaverages = DB::select("
            SELECT *
            FROM `leagueaverages`
            WHERE `eventid` = :eventid"
            ,['eventid' => $event->eventid]);

        //dump($scoreaverages);
        $sortedscores = [];

        // loop through all THIS WEEKS SCORES
        foreach ($scores as $score) {

            // Find the user id, once found, the number of score entries must be more than 1 to be able to get points
            foreach ($scoreaverages as $average) {

                if (($average->userid == $score->userid && $average->divisionid == $score->divisionid)&& $average->scorecount > 1) {
                    $score->avg_total_score = $average->avg_total_score;
                    $score->avg_total_10 = $average->avg_total_10;
                    $score->avg_total_x = $average->avg_total_x;
                    $score->handicap_value = $eventroundmax - $average->avg_total_score;
                    $score->handicap_score = $score->total + $score->handicap_value;

                    // Add to the array
                    $sortedscores[$score->divisionid][] = $score;

                }
            }
        }

        // To here we have the all the info we need to assign points

        foreach ($sortedscores as $divisionid => $divisionscores) {

            // Sort the divisions scores from highest to lowest
            usort($divisionscores, function($a, $b) {

                // return 1 when B greater than A

                if ($b->handicap_score == $a->handicap_score) {
                    if ($b->avg_total_score == $a->avg_total_score) {
                        if ($b->avg_total_10 == $a->avg_total_10) {
                            if ($b->avg_total_x > $a->avg_total_x) {
                                return 1;
                            }
                            return -1;
                        }

                        if ($b->avg_total_10 > $a->avg_total_10) {
                            return 1;
                        }

                        return -1;

                    }

                    if ($b->avg_total_score > $a->avg_total_score) {
                        return 1;
                    }
                    return -1;
                }

                // B greater than A
                if ($b->handicap_score > $a->handicap_score) {
                    return 1;
                }

                return -1;

            });


            // Start at 10 points, loop through awarding points . Stop when at xero
            $points = 10;
            foreach ($divisionscores as $divisionscore) {
                $leaguepoint = new LeaguePoint();
                $leaguepoint->userid = $divisionscore->userid;
                $leaguepoint->eventid = $divisionscore->eventid;
                $leaguepoint->divisionid = $divisionid;
                $leaguepoint->week = $divisionscore->week;
                $leaguepoint->points = $points--;

                $leaguepoint->save();

                if ($points == 0) {
                    break;
                }

            }
        }

        $eventcompetition->currentweek++;
        $eventcompetition->save();

        return response()->json([
            'success' => true,

        ]);

    }

}
