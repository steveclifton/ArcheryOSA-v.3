<?php

namespace App\Http\Controllers\Events\PublicEvents\Event;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Support\Facades\DB;

class EventResultsController extends Controller
{

    /**
     * Returns the overall results for an event - consolidated
     * @param Event $event
     * @param bool $apicall
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\View\View|\stdClass
     */
    public function getOverallResults(Event $event, $apicall = false)
    {
        // get and format the Event Competitions
        $ectmp = EventCompetition::where('eventid', $event->eventid)->orderby('sequence')->get();
        $eventcompetitions = $competitionlabels = [];
        foreach ($ectmp as $e) {
            $eventcompetitions[$e->eventcompetitionid] = $e;
            $competitionlabels[$e->eventcompetitionid] = $e->label . ' - ' . date('d M', strtotime($e->date));
        }
        unset($ectmp);


        // get all the scores once, sort them
        $flatscores = DB::select("
            SELECT sf.*, r.label as roundname, r.unit, ec.date as compdate, ec.sequence, `e`.`eventtypeid`, ee.firstname, ee.lastname, ee.gender, u.username, d.label as division, d.bowtype
            FROM `scores_flat` sf
            JOIN `events` e ON (`e`.`eventid` = `sf`.`eventid`)
            JOIN `evententrys` ee ON (sf.entryid = ee.entryid)
            LEFT JOIN `users` u ON (ee.userid = u.userid)
            JOIN `rounds` r USING (`roundid`)
            JOIN `eventcompetitions` ec ON (sf.`eventcompetitionid` = ec.`eventcompetitionid`)
            JOIN `divisions` d on (sf.divisionid = d.divisionid)
            WHERE sf.`eventid` = :eventid
        ", ['eventid' => $event->eventid]);

        $archers = [];
        // Sort into array of archers and eventcompids
        foreach ($flatscores as $entry) {

            // sometimes people change divisions, keep the sorting user/division specific
            $key = $entry->userid . $entry->divisionid;

            $archers[$key][$entry->eventcompetitionid] = $entry;

            if (empty($archers[$key]['total'])) {
                $archers[$key]['total'] = 0;
            }
            $archers[$key]['total'] += $entry->total;
        }

        // Sort by keys
        $final = [];
        foreach ($archers as $archer) {
            ksort($archer, SORT_STRING);

            $result = [];
            $rounds = [];

            // Load in all the competition days
            // Create the rounds here as they are sequenced correctly
            foreach ($competitionlabels as $ecid => $label) {
                $result[$ecid] = '';
                $rounds[$ecid] = '';
            }

            // Loop over the Archer's scores
            $key = '';
            $bowtype = '';

            foreach ($archer as $eventcompid => $score) {

                if (empty($result['archer'])) {
                    $result['archer'] = '<a href="/profile/public/'.$score->username.'">' . htmlentities(ucwords($score->firstname . ' ' . $score->lastname)) . '</a>';
                }

                if ($eventcompid == 'total') {
                    $result['total'] = $score;
                    continue;
                }

                $result[$eventcompid] = $score->total;

                if (empty($key)) {
                    $key = ($score->gender == 'm' ? "Mens" : "Womens") . ' ' . $score->division;
                }
                if (empty($bowtype)) {
                    $bowtype = $score->bowtype;
                }

                // Update the round name
                if (empty($rounds[$score->eventcompetitionid])) {
                    $rounds[$score->eventcompetitionid] = $score->roundname;
                }
            }


            // Loop over each round that was set and update if its not empty
            foreach ($rounds as $ecid => $round) {
                if (empty($final[$bowtype][$key]['rounds'][$ecid])) {
                    $final[$bowtype][$key]['rounds'][$ecid] = $round;
                }
            }

            $final[$bowtype][$key][] = $result;

        }

        // sort the bowtypes
        ksort($final);

        $results = $this->sorttotalresults($final);
        unset($final);

        $data = compact('event', 'results', 'competitionlabels');
        if ($apicall) {
            return $data;
        }

        return view('events.results.event.results-overall', $data);
    }



    /**
     * Returns an events individual competitions results
     *
     * @param Event $event
     * @param $eventcompetitionid
     * @param bool $apicall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEventCompetitionResults(Event $event, $eventcompetitionid, $apicall = false)
    {

        $scores = DB::select("
            SELECT sf.*, ee.firstname, ee.lastname, ee.gender, ec.entrycompetitionid, r.label as roundname, d.label as division,
                ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit, u.username, c.label as clubname
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `users` u on (ee.userid = u.userid)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
            LEFT JOIN `clubs` c ON (c.clubid = ee.clubid)
            WHERE `ee`.`eventid` = :eventid
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label
        ", ['eventcompetitionid' => $eventcompetitionid, 'eventid' => $event->eventid]);


        // Create required Data
        $results = [];
        foreach ($scores as $score) {
            $archer = [];
            $archer['archer'] = '<a href="/profile/public/'.$score->username.'">' . htmlentities(ucwords($score->firstname . ' ' . $score->lastname)) . '</a>';
            $archer['club'] = ($score->club ?? '');
            $archer['round'] = ($score->roundname ?? '');
            $archer['dist1'] = ($score->dist1score ?? '');
            $archer['dist2'] = ($score->dist2score ?? '');
            $archer['dist3'] = ($score->dist3score ?? '');
            $archer['dist4'] = ($score->dist4score ?? '');
            $archer['total'] = ($score->total ?? '');

            $key = ($score->gender == 'm' ? "Mens" : "Womens") . ' ' . $score->division;
            $bowtype = $score->bowtype;

            if (empty($results[$bowtype][$key]['rounds'])) {
                $results[$bowtype][$key]['rounds'] = [];
            }

            $this->addroundtoresults($score, $results, $bowtype, $key);

            // Add result to the array
            $results[$bowtype][$key][] = $archer;
        }

        $results = $this->sorttotalresults($results);

        $eventcompetition = EventCompetition::where('eventcompetitionid', $eventcompetitionid)->first();

        $data = compact('event', 'results', 'eventcompetition');

        if (!empty($apicall)) {
            return $data;
        }

        return view('events.results.event.results', $data);

    }

    private function sorttotalresults($results)
    {
        $return = [];

        foreach ($results as $bowtype => $r) {

            // HERE I NEED TO SORT THE DIVISIONS BY A SEQUENCE YET TO BE ADDED TO DIVISIONS
            ksort($r);

            foreach ($r as &$res) {

                // Sort each divisions results by highest first
                uasort($res, function ($a, $b) {

                    if (empty($a['total']) || empty($b['total'])) {
                        return 0;
                    }

                    if ((int)$b['total'] > (int)$a['total']) {
                        return 1;
                    }
                    else if ((int)$b['total'] < (int)$a['total']) {
                        return -1;
                    }

                    return 0;
                });
            }

            $return = array_merge($return, $r);
        }

        return $return;
    }


    protected function addroundtoresults($score, &$results, $bowtype, $key)
    {
        // Build up the rounds details on each result iteration in
        $results[$bowtype][$key]['rounds']['dist1'] = isset($results[$bowtype][$key]['rounds']['dist1'])
            ? $results[$bowtype][$key]['rounds']['dist1']
            : ($score->dist1 ?? '');

        $results[$bowtype][$key]['rounds']['dist2'] = isset($results[$bowtype][$key]['rounds']['dist2'])
            ? $results[$bowtype][$key]['rounds']['dist2']
            : ($score->dist2 ?? '');

        $results[$bowtype][$key]['rounds']['dist3'] = isset($results[$bowtype][$key]['rounds']['dist3'])
            ? $results[$bowtype][$key]['rounds']['dist3']
            : ($score->dist3 ?? '');

        $results[$bowtype][$key]['rounds']['dist4'] = isset($results[$bowtype][$key]['rounds']['dist4'])
            ? $results[$bowtype][$key]['rounds']['dist4']
            : ($score->dist4 ?? '');

        $results[$bowtype][$key]['rounds']['total'] = 'Total';

        $results[$bowtype][$key]['rounds']['unit'] = isset($results[$bowtype][$key]['rounds']['unit'])
            ? $results[$bowtype][$key]['rounds']['unit']
            : ($score->unit ?? '');

        return null;
    }


    /**
     * Old Method, needs to be updated
     *  - Only used now in the profilecontroller
     */
    public function formatOverallResults($entrys, $flatscores)
    {
        $numberofec = count(array_column($flatscores, 'eventcompetitionid', 'eventcompetitionid'));

        $eventcompseq = $flatscoressorted = [];
        foreach ($flatscores as $flatscore) {
            // Add scores to a UserID KEY'd array
            $flatscoressorted[$flatscore->userid][] = $flatscore;

            // reformat the round name
            $flatscore->roundname = $flatscore->roundname . date(' - d M', strtotime($flatscore->compdate)) . '|' .$flatscore->eventcompetitionid;

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
                    if ((!empty($flatscore->eventtypeid) && $flatscore->eventtypeid === 1) && ($entry->divisionid == $flatscore->divisionid)) {
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
                    foreach (array_keys($round->score) as $key) {
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

                    if (count($ecomp) < $numberofec) {
                        foreach (range(count($ecomp), $numberofec - 1) as $i) {
                            $result[$i] = '';
                        }
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

}
