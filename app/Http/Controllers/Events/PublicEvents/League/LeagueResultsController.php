<?php

namespace App\Http\Controllers\Events\PublicEvents\League;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserResults;
use App\Models\Division;
use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeagueResultsController extends Controller
{
    use UserResults;


    /**
     * Returns a league events overall results
     * @param Event $event
     * @param bool $apicall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLeagueOverallResults(Event $event, $apicall = false)
    {
        $entrys = $this->getEventEntrySorted($event->eventid);

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)->first();

        $evententrys = [];
        foreach ($entrys as $entry) {
            $entry->top10 = $this->getUserTop10Scores($entry->userid, $entry->divisionid, $event->eventid);

            if (empty($entry->top10->total)) {
                continue;
            }

            $entry->average     = $this->getUserAverage($entry->userid, $entry->divisionid, $event->eventid);
            $entry->top10points = $this->getUserTop10Points($entry->userid, $entry->divisionid, $event->eventid);

            $gender = '';
            if (!$eventcompetition->ignoregenders) {
                $entry->gender == 'm' ? 'Mens ' : 'Womens ';
            }

            // Remove Userid For now
            if (!empty($apicall)) {
                unset($entry->userid);
            }

            $evententrys[$entry->bowtype][$gender . $entry->divisionname][$entry->username] = $entry;
        }

        $data = compact('event', 'evententrys', 'eventcompetition');

        if (!empty($apicall)) {
            return $data;
        }

        return view('events.results.league.leagueresults-overall', $data);
    }


    /**
     * Returns a particular weeks results for a league event
     *
     * @param Event $event
     * @param $week
     * @param bool $apicall
     * @param $userid
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLeagueCompetitionResults(Event $event, $week, $apicall = false, $userid = null)
    {
        $and = '';
        $args = ['eventid' => $event->eventid, 'week' => $week, 'week2' => $week];
        if (!empty($userid)) {
            $and = ' AND `ee`.`userid` = :userid ';
            $args['userid'] = $userid;
        }

        $entrys = DB::select("
            SELECT ee.firstname, ee.lastname, ee.gender, ec.entrycompetitionid, 
                ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit,
                sf.*, lp.points, u.username
            FROM `evententrys` ee
            JOIN `users` u ON (ee.userid = u.userid)
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
            LEFT JOIN `leaguepoints` lp ON (ee.userid = lp.userid AND ee.eventid = lp.eventid AND ec.divisionid = lp.divisionid AND lp.week = :week2)
            WHERE `ee`.`eventid` = :eventid
            AND `sf`.total <> 0
            AND `sf`.`week` = :week
            AND `ee`.`entrystatusid` = 2
            $and
            ORDER BY `d`.label
        ", $args);


        if ($apicall && empty($entrys)) {
            return [];
        }

        else if (empty($entrys)) {
            return back()->with('failure', 'Unable to get results');
        }

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)->first();


        $evententrys = [];
        foreach ($entrys as $entry) {
            $gender = '';
            if (!$eventcompetition->ignoregenders) {
                $entry->gender == 'm' ? 'Mens ' : 'Womens ';
            }

            // Remove Userid For now
            if (!empty($apicall)) {
                unset($entry->userid);
            }

            $evententrys[$entry->bowtype][$gender . $entry->divisionname][] = $entry;
        }

        $data = compact('event', 'evententrys', 'eventcompetition', 'week');

        if (!empty($apicall)) {
            return $data;
        }
        return view('events.results.league.leagueresults', $data);
    }


    /**
     * Returns the event's entrys sorted
     * @param $eventid
     * @return array|bool|mixed
     */
    public function getEventEntrySorted($eventid, $userid = null, $groupbyentry = false)
    {
        $and = '';
        $args = ['eventid' => $eventid];
        if (!empty($userid)) {
            $and = ' AND `ee`.`userid` = :userid ';
            $args['userid'] = $userid;
        }

        $groupby = '';
        if (!empty($groupbyentry)) {
            $groupby = " GROUP BY `ee`.`entryid` ";
        }

        $entrys = DB::select("
            SELECT ee.userid, ee.firstname, ee.lastname, ee.gender, ec.roundid, ec.divisionid,  
                  d.label as divisionname, d.bowtype, r.unit, r.code, r.label as roundname, s.label as schoolname, u.username
            FROM `evententrys` ee
            JOIN `users` u ON (ee.userid = u.userid)
            JOIN `entrycompetitions` ec ON (ec.`entryid` = ee.`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND `sf`.`divisionid` = ec.divisionid)
            LEFT JOIN `schools` s ON (ee.schoolid = s.schoolid)
            WHERE `ee`.`eventid` = :eventid
            AND `ee`.`entrystatusid` = 2
            $and 
            $groupby
            ORDER BY d.label, ee.userid, ec.eventcompetitionid
        ", $args);

        // Get all the divisions
        static $alldivisions;
        if (empty($alldivisions)) {
            $alldivisions = Division::all()->keyBy('divisionid')->toArray();
        }


        $sortedEntrys = [];

        foreach ($entrys as $entry) {
            if (strpos($entry->divisionid, ',') !== false) {
                $divisionids = explode(',', $entry->divisionid);

                foreach ($divisionids as $divisionid) {
                    // clone the entry
                    $entryUpdated = clone $entry;
                    $divison = (object) $alldivisions[$divisionid];

                    $entryUpdated->bowtype = $divison->bowtype;
                    $entryUpdated->divisionname = $divison->label;
                    $entryUpdated->divisionid = $divisionid;
                    $sortedEntrys[] = $entryUpdated;
                }
            }
            else {
                $sortedEntrys[] = $entry;
            }
        }

        return $sortedEntrys;

    }

}
