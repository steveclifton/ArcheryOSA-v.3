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

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

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
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $overall = $event->showoverall;

        // league event
        if ($event->isLeague()) {
            $eventcompetition = EventCompetition::where('eventid', $event->eventid)->get()->first();

            $rangeArr = [];
            foreach (range(1, $eventcompetition->currentweek) as $week) {
                $score = Score::where('eventid', $eventcompetition->eventid)
                    ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                    ->where('week', $week)
                    ->get()
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
                                            ->get()
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
        $entrys = $this->getEventEntrySorted($event->eventid);

        // get all the scores once, sort them
        $flatscores = DB::select("
            SELECT sf.*, r.label as roundname, r.unit, ec.date as compdate
            FROM `scores_flat` sf
            JOIN `rounds` r USING (`roundid`)
            JOIN `eventcompetitions` ec ON (sf.`eventcompetitionid` = ec.`eventcompetitionid`)
            WHERE sf.`eventid` = :eventid
        ", ['eventid' => $event->eventid]);

        $eventcomp = [];

        $flatscoressorted = [];
        foreach ($flatscores as $flatscore) {
            $flatscoressorted[$flatscore->userid][] = $flatscore;
            if (empty($eventcomp[$flatscore->eventcompetitionid])) {
                $eventcomp[$flatscore->eventcompetitionid] = $flatscore->eventcompetitionid;
            }
        }

        // loop over the scores and find the one that matches the div and round
        foreach ($entrys as $entry) {
            if (!empty($flatscoressorted[$entry->userid])) {
                // they have scores, find the score that matches the details
                foreach($flatscoressorted[$entry->userid] as $flatscore) {

                    if ($event->isEvent()) {
                        $entry->score[] = $flatscore;
                        continue;
                    }

                    // league stuff needs to be checked
                    $divMatch = $entry->divisionid == $flatscore->divisionid;
                    $roundMatch = $entry->roundid == $flatscore->roundid;

                    if ($divMatch && $roundMatch) {
                        $entry->score[] = $flatscore;
                    }
                }
            }
        }

        $evententrys = [];
        foreach ($entrys as $key => $entry) {
            if (empty($entry->score)) {
                unset($entrys[$key]);
                continue;
            }
            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            $evententrys[$entry->bowtype][$gender . $entry->divisionname][$entry->userid] = $entry;
        }


        $finalResults = [];
        foreach ($evententrys as $bowtype => $div) {

            foreach ($div as $divname => $archers) {

                $rounds = [];

                foreach($archers as $a) {
                    if (count($a->score) > count($rounds)) {
                        // empty the array, rebuilt
                        $rounds = [];
                        $i = 1;
                        foreach ($a->score as $score) {
                            $rounds[$score->eventcompetitionid] = $score->roundname;
                        }
                    }
                }

                // $rounds is the list of eventcompetitions shot for this event. They will be in date order

                // here is the list of archers results for this particular bowtype and division
                // combine the results
                foreach ($archers as $archer) {

                    $data = new \stdClass();
                    $data->name     = $archer->firstname . ' ' . $archer->lastname;

                    if (!empty($archer->score)) {

                        foreach ($archer->score as $score) {

                            // find the position of the 
                            $i = 1;
                            foreach($rounds as $key => $name) {
                                if ($key == $score->eventcompetitionid) {
                                    break;
                                }
                                $i++;
                            }

                            $dist           = 'dist' . $i;
                            $data->{$dist}  = $score->roundname;
                            $dist           = 'dist' . $i++ . 'score';
                            $data->{$dist}  = $score->total;
                            if (empty($data->total)) {
                                $data->total = $score->total;
                            }
                            else {
                                $data->total += $score->total;
                            }
                        }

                        if (count($archer->score) < count($eventcomp)) {
                            $difference = count($eventcomp) - count($archer->score);

                            foreach (range($i--, $i + $difference) as $j) {
                                $dist           = 'dist' . $j;
                                $data->{$dist}  = '';
                                $dist           = 'dist' . $j++ . 'score';
                                $data->{$dist}  = 0;
                            }
                        }
                        $finalResults[$bowtype][$divname][] = $data;
                    }
                }

                $j = 1;
                // now go over and make sure the first item has the correct titles
                foreach ($finalResults[$bowtype][$divname] as $key => $archer) {
                    $dist = 'dist' . count($eventcomp);

                    if (empty($archer->{$dist}) && (count($finalResults[$bowtype][$divname]) > $j++)) {
                        $a = $finalResults[$bowtype][$divname][$key];
                        unset($finalResults[$bowtype][$divname][$key]);
                        $finalResults[$bowtype][$divname][] = $a;
                    }
                }

                // sort based on total
                usort($finalResults[$bowtype][$divname], function($a, $b) {
                   return $a->total < $b->total;
                });
            }

        }

        $data = compact('event', 'finalResults');

        if ($apicall) {
            return $data;
        }

        return view('events.results.results-overall', $data);

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
                sf.*
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
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

        $eventcompetition = EventCompetition::where('eventcompetitionid', $eventcompetitionid)->get()->first();

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

        $entrys = $this->getEventEntrySorted($event->eventid);

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)->get()->first();


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
     * @param Event $event
     * @param $week
     * @param bool $apicall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getLeagueCompetitionResults(Event $event, $week, $apicall = false)
    {
        $entrys = DB::select("
            SELECT ee.firstname, ee.lastname, ee.gender, ec.entrycompetitionid, 
                ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit,
                sf.*, lp.points
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
            LEFT JOIN `leaguepoints` lp ON (ee.userid = lp.userid AND ee.eventid = lp.eventid AND ec.divisionid = lp.divisionid AND lp.week = '{$week}')
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `sf`.total <> 0
            AND `sf`.`week` = :week
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label
        ", ['week' => $week]);

        if ($apicall && empty($entrys)) {
            return [];
        }

        else if (empty($entrys)) {
            return back()->with('failure', 'Unable to get results');
        }

        $eventcompetition = EventCompetition::where('eventid', $event->eventid)->get()->first();


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
    public function getEventEntrySorted($eventid)
    {
        $entrys = $this->getcacheditem('evententrys-' . $eventid);

        if (empty($entrys)) {
            $entrys = DB::select("
            SELECT ee.userid, ee.firstname, ee.lastname, ee.gender, ec.roundid, ee.divisionid,  
                  d.label as divisionname, d.bowtype, r.unit, r.label as roundname, r.code
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            JOIN `scores_flat` sf ON (ee.entryid = sf.entryid)
            WHERE `ee`.`eventid` = '".$eventid."'
            AND `ee`.`entrystatusid` = 2
            GROUP BY `ee`.`entryid`
            ORDER BY d.label, ee.userid, ec.eventcompetitionid
            ");

            $sortedEntrys = [];
            foreach ($entrys as $entry) {
                if (strpos($entry->divisionid, ',') !== false) {
                    $divisionids = explode(',', $entry->divisionid);

                    foreach ($divisionids as $divisionid) {
                        // clone the entry
                        $entryUpdated = clone $entry;
                        $divison = Division::where('divisionid', $divisionid)->get()->first();

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

            $entrys = $sortedEntrys;

            Cache::put('evententrys-' . $eventid, $entrys, 60);
        }


        return $entrys;
    }
}
