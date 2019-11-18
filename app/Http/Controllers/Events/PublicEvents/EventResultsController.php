<?php

namespace App\Http\Controllers\Events\PublicEvents;

use App\Models\Division;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\FlatScore;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Traits\UserResults;


class EventResultsController extends EventController
{
    use UserResults;


    /**
     * MAIN entry point into get results.
     *  - Does filtering between league and events
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getCompetitionResults(Request $request)
    {
        if (empty($request->eventcompetitionid) || empty($request->eventurl)) {
            return back()->with('failure', 'Invalid Request');
        }

        $event = Event::where('eventurl', $request->eventurl)->first();

        if (strcasecmp($request->eventcompetitionid, 'overall') === 0) {
            // league processing
            if ($event->isLeague()) {
                return $this->getLeagueOverallResults($event);
            }

            // Normal Event
            return $this->getEventOverallResults($event);
        }



        // league processing
        if ($event->isLeague()) {
            return $this->getLeagueCompetitionResults($event, $request->eventcompetitionid);
        }

        // Get the results for the event and the eventcompetitionid
        return $this->getEventCompResults($event, $request->eventcompetitionid);


    }



    /**
     * Get the Events competitions and their results status
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory| \Illuminate\Http\RedirectResponse| \Illuminate\Routing\Redirector| \Illuminate\View\View
     */
    public function getEventResultsList(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            return redirect('/');
        }

        $overall = $event->showoverall;

        // league event
        if ($event->isLeague()) {
            $eventcompetition = EventCompetition::where('eventid', $event->eventid)->first();

            $rangeArr = [];
            foreach (range(1, $eventcompetition->currentweek) as $week) {
                $score = Score::where('eventid', $eventcompetition->eventid)
                    ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                    ->where('week', $week)
                    ->first();

                if (!empty($score)) {
                    $rangeArr[] = $week;
                }
            }

            return view('events.results.league.leaguecompetitions', compact('event', 'rangeArr', 'overall'));
        }


        // not a league
        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->orderBy('date', 'asc')->get();
        $haveScores = false;
        foreach ($eventcompetitions as $eventcompetition) {

            $eventcompetition->score = Score::where('eventid', $eventcompetition->eventid)
                                            ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                                            ->first();
            if (empty($haveScores) && !empty($eventcompetition->score)) {
                $haveScores = true;
            }

        }

        // dont show overall if there are no results
        if ($overall && !$haveScores) {
            $overall = false;
        }
        return view('events.results.eventcompetitions', compact('event', 'eventcompetitions', 'overall'));
    }


    /**
     * Returns the overall results for an event - consolidated
     * @param Event $event
     * @param bool $apicall
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\stdClass
     */
    public function getEventOverallResults(Event $event, $apicall = false)
    {
        $entrys = $this->getEventEntrySorted($event->eventid, null, true);


        // get all the scores once, sort them
        $flatscores = DB::select("
            SELECT sf.*, CONCAT_WS(' ', r.label, ec.label) as roundname, r.unit, ec.date as compdate, ec.sequence,
                    `e`.`eventtypeid`
            FROM `scores_flat` sf
            JOIN `events` e ON (`e`.`eventid` = `sf`.`eventid`)
            JOIN `rounds` r USING (`roundid`)
            JOIN `eventcompetitions` ec ON (sf.`eventcompetitionid` = ec.`eventcompetitionid`)
            WHERE sf.`eventid` = :eventid
        ", ['eventid' => $event->eventid]);

        $finalResults = $this->formatOverallResults($entrys, $flatscores);

        $data = compact('event', 'finalResults');
        if ($apicall) {
            return $data;
        }

        return view('events.results.results-overall', $data);

    }


    /**
     * Formats results for overall
     * @param $entrys
     * @param $flatscores
     * @return array
     */
    public function formatOverallResults($entrys, $flatscores)
    {

        $eventcompseq = $flatscoressorted = [];
        foreach ($flatscores as $flatscore) {
            // Add scores to a UserID KEY'd array
            $flatscoressorted[$flatscore->userid][] = $flatscore;

            $eventcompseq[$flatscore->roundname] = $flatscore->sequence;

        }

        // loop over the scores and find the one that matches the div and round
        foreach ($entrys as $key => $entry) {

            if (!empty($flatscoressorted[$entry->userid])) {

                // create the array for the entry's scores
                $entry->score = [];

                // they have scores, find the score that matches the details
                foreach ($flatscoressorted[$entry->userid] as $flatscore) {

                    // if its an event
                    if (($flatscore->eventid === 1) && ($entry->divisionid == $flatscore->divisionid)) {
                        $entry->score[$flatscore->roundname] = $flatscore->total;
                        continue;
                    }

                    // league stuff needs to be checked
                    $divMatch = $entry->divisionid == $flatscore->divisionid;
                    $roundMatch = $entry->roundid == $flatscore->roundid;

                    if ($divMatch && $roundMatch) {
                        $entry->score[$flatscore->roundname] = $flatscore->total;
                    }
                }
            }
            else {
                // remove the entry
                unset($entrys[$key]);
            }
        }

        $finalResults = [];
        foreach ($entrys as $key => $entry) {

            // Make sure they have a score
            if (empty($entry->score)) {
                unset($entrys[$key]);
                continue;
            }

            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';

            $key = $gender . $entry->divisionname . ' - ' . ($entry->roundname);

            $finalResults[$entry->bowtype][$key][] = $entry;
        }

        // Sort by sequence
        foreach ($finalResults as $bowtype => &$divisions) {
            foreach ($divisions as $divisionname => &$rounds) {

                // Build an array of all the round names
                $ecomp = [];
                foreach ($rounds as $round) {
                    foreach(array_keys($round->score) as $key) {
                        $ecomp[$key] = $key;
                    }
                }

                // Sort them by the sequence
                uksort($ecomp, function($a, $b) use ($eventcompseq) {
                    if (!isset($eventcompseq[$a]) || !isset($eventcompseq[$b])) {
                        return -1;
                    }
                    if ($eventcompseq[$a] > $eventcompseq[$b]) {
                        return 1;
                    }
                    if ($eventcompseq[$a] < $eventcompseq[$b]) {
                        return -1;
                    }
                    return 0;
                });


                // Add users results into the results array
                foreach ($rounds as $archer) {

                    $result = [];
                    $result['Archer'] = '<a href="/profile/public/'.$archer->username.'">' . ucwords($archer->firstname . ' ' . $archer->lastname) . '</a>';

                    if (!empty($archer->schoolname)) {
                        $result['School'] = ucwords($archer->schoolname);
                    }

                    foreach($ecomp as $key) {
                        $result[$key] = '';
                    }

                    $totalscore = 0;
                    foreach ($archer->score as $roundname => $score) {
                        $result[$roundname] = $score;
                        $totalscore += $score;
                    }
                    $result['Total'] = $totalscore;
                    $divisions[$divisionname]['results'][] = $result;
                }

                // Sort the results by Total
                usort($divisions[$divisionname]['results'], function($a, $b) {
                    if ($a['Total'] == $b['Total']) {
                        return 0;
                    }
                    if ($a['Total'] < $b['Total']) {
                        return 1;
                    }
                    if ($a['Total'] > $b['Total']) {
                        return -1;
                    }
                    return 0;
                });

            }
        }

        return $finalResults;
    }

    /**
     * Returns an events individual competitions results
     *
     * @param Event $event
     * @param $eventcompetitionid
     * @param bool $apicall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEventCompResults(Event $event, $eventcompetitionid, $apicall = false)
    {
        $entrys = DB::select("
            SELECT ee.firstname, ee.lastname, ee.gender, ec.entrycompetitionid, 
                ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit,
                sf.*, u.username
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `users` u on (ee.userid = u.userid)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label
        ", ['eventcompetitionid' => $eventcompetitionid]);


        $evententrys = [];
        foreach ($entrys as $entry) {
            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            $evententrys[$entry->bowtype][$gender . $entry->divisionname][] = $entry;

            if (!empty($apicall)) {
                unset($entry->userid);
            }
        }

        $eventcompetition = EventCompetition::where('eventcompetitionid', $eventcompetitionid)->first();

        $data = compact('event', 'evententrys', 'eventcompetition');

        if (!empty($apicall)) {
            return $data;
        }


        return view('events.results.results', $data);

    }


    /**
     * Returns a league events overall results
     * @param Event $event
     * @param bool $apicall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getLeagueOverallResults(Event $event, $apicall = false)
    {
        $entrys = $this->getEventEntrySorted($event->eventid, null, true);
        
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

            $evententrys[$entry->bowtype][$gender . $entry->divisionname][] = $entry;
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
