<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventCompetition;
use Illuminate\Support\Facades\DB;

class EventResultService
{
    protected ScoringService $scoringService;

    public function __construct(?ScoringService $scoringService = null)
    {
        $this->scoringService = $scoringService ?? new ScoringService();
    }

    protected function sortTotalOverallResults($results)
    {
        $return = [];

        foreach ($results as $bowtype => $r) {

            // HERE I NEED TO SORT THE DIVISIONS BY A SEQUENCE YET TO BE ADDED TO DIVISIONS
            ksort($r);

            foreach ($r as &$res) {
                $rounds = $res['rounds'];
                unset($res['rounds']);

                // Sort each divisions results by highest first
                uasort($res, function ($a, $b) {
                    if (empty($a['total']) && empty($b['total'])) {
                        return 0;
                    }

                    if ((int)$b['total'] > (int)$a['total']) {
                        return 1;
                    }
                    if ((int)$b['total'] < (int)$a['total']) {
                        return -1;
                    }

                    if ((int)$b['total'] != (int)$a['total']) {
                        return 0;
                    }

                    if ((int)$b['inners'] > (int)$a['inners']) {
                        return 1;
                    }
                    if ((int)$b['inners'] < (int)$a['inners']) {
                        return -1;
                    }

                    return 0;
                });
                $res['rounds'] = $rounds;
            }

            $return = array_merge($return, $r);
        }

        return $return;
    }

    protected function getRound($score)
    {
        return [
            'dist1' => $score->dist1 ?? '',
            'dist2' => $score->dist2 ?? '',
            'dist3' => $score->dist3 ?? '',
            'dist4' => $score->dist4 ?? '',
            'total' => 'Total',
            'unit' => $score->unit ?? '',
        ];
    }

    public function getEventCompetitionResults(Event $event, int $eventCompetitionId, bool $apiCall = false )
    {
        $returnData = [];

        $eventCompetition = EventCompetition::where('eventcompetitionid', $eventCompetitionId)
            ->where('eventid', $event->eventid)
            ->first();

        if (!$eventCompetition) {
            if ($apiCall) {
                return $returnData;
            }
            return back()->with('failure', 'Unable to process request');
        }

        $returnData['eventcompetition'] = $eventCompetition;
        $returnData['event'] = $event;

        $scores = $this->getEventCompetitionScores($event->eventid, $eventCompetitionId);

        if (empty($scores)) {
            if ($apiCall) {
                return $returnData;
            }

            return back()->with('failure', 'Unable to process request');
        }

        $results = $this->buildEventCompetitionResults($scores);
        $returnData['results'] = $this->scoringService
            ->setResults($results)
            ->sort()
            ->getSortedResults();

        if ($apiCall) {
            return $returnData;
        }

        return view('events.results.event.results', $returnData);
    }

    protected function getEventCompetitionScores(int $eventId, int $eventCompetitionId): array
    {
        return DB::table('evententrys as ee')
            ->join('entrycompetitions as ec', 'ec.entryid', '=', 'ee.entryid')
            ->join('users as u', 'u.userid', '=', 'ee.userid')
            ->join('divisions as d', 'd.divisionid', '=', 'ec.divisionid')
            ->join('rounds as r', 'r.roundid', '=', 'ec.roundid')
            ->join('scores_flat as sf', function ($join) {
                $join->on('sf.entryid', '=', 'ee.entryid')
                    ->on('sf.entrycompetitionid', '=', 'ec.entrycompetitionid')
                    ->on('sf.roundid', '=', 'ec.roundid');
            })
            ->where('ee.eventid', $eventId)
            ->where('ec.eventcompetitionid', $eventCompetitionId)
            ->where('ee.entrystatusid', 2)
            ->orderBy('d.label')
            ->select([
                'ee.firstname',
                'ee.lastname',
                'ee.gender',
                'u.username',
                'd.label as division',
                'r.label as roundname',
                'r.unit',
                'sf.dist1',
                'sf.dist2',
                'sf.dist3',
                'sf.dist4',
                'sf.dist1score',
                'sf.dist2score',
                'sf.dist3score',
                'sf.dist4score',
                'sf.total',
                'sf.inners',
                'sf.max',
            ])
            ->get()
            ->all();
    }

    protected function buildEventCompetitionResults(array $scores): array
    {
        $results = [];

        foreach ($scores as $score) {
            $key = sprintf('%s %s', $score->division, $score->gender == 'm' ? 'Men' : 'Women');

            if (empty($results[$key]['rounds'])) {
                $results[$key]['rounds'] = $this->getRound($score);
            }

            $results[$key][] = [
                'archer' => '<a href="/profile/public/'.$score->username.'">' . htmlentities(ucwords($score->firstname . ' ' . $score->lastname)) . '</a>',
                'round' => $score->roundname ?? '',
                'dist1' => $score->dist1score ?? null,
                'dist2' => $score->dist2score ?? null,
                'dist3' => $score->dist3score ?? null,
                'dist4' => $score->dist4score ?? null,
                'total' => $score->total ?? '',
                'inners' => $score->inners ?? '',
                'xcount' => $score->max ?? '',
            ];
        }

        return $results;
    }


    /**
     * Get the overall results for an event, sorted by total score and inners
     *
     * @param Event $event
     * @param bool $apiCall
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEventOverallResults(Event $event, bool $apiCall = false)
    {
        $returnData = [];

        // get and format the Event Competitions
        $ectmp = EventCompetition::where('eventid', $event->eventid)->orderby('sequence')->get();
        $eventcompetitions = $competitionlabels = [];
        foreach ($ectmp as $e) {
            $eventcompetitions[$e->eventcompetitionid] = $e;
            $competitionlabels[$e->eventcompetitionid] = $e->label . ' - ' . date('d M', strtotime($e->date));
        }
        unset($ectmp);

        $returnData['competitionlabels'] = $competitionlabels;

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
            ORDER BY `sf`.`total` DESC
        ", ['eventid' => $event->eventid]);


        if ($apiCall && empty($flatscores)) {
            return $returnData;
        }
        else if (empty($flatscores)) {
            return back()->with('failure', 'Unable to process request');
        }

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
                $result['inners'] = $score->inners;
                $result['xcount'] = $score->max;

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

        $results = $this->sortTotalOverallResults($final);
        unset($final);

        $returnData['results'] = $results;
        $returnData['event'] = $event;

        if ($apiCall) {
            return $returnData;
        }

        return view('events.results.event.results-overall', $returnData);
    }

}
