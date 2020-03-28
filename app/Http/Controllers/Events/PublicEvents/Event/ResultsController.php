<?php

namespace App\Http\Controllers\Events\PublicEvents\Event;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\UserResults;
use App\Models\Division;
use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mpdf\Tag\P;

class ResultsController extends Controller
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

        $results = [];
        foreach ($final as $bowtype => $r) {

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

            $results = array_merge($results, $r);
        }
        unset($final);

        $data = compact('event', 'results', 'competitionlabels');
        if ($apicall) {
            return $data;
        }

        return view('events.results.events.results-overall', $data);
    }

}
