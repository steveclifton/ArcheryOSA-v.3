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
        $competitionlabels = $this->getEventCompetitionLabels($event->eventid);
        $returnData = [
            'competitionlabels' => $competitionlabels,
            'event' => $event,
        ];

        $flatscores = $this->getEventOverallFlatScores($event->eventid);

        if (empty($flatscores)) {
            if ($apiCall) {
                return $returnData;
            }

            return back()->with('failure', 'Unable to process request');
        }

        $returnData['results'] = $this->buildEventOverallResults($flatscores, $competitionlabels);

        if ($apiCall) {
            return $returnData;
        }

        return view('events.results.event.results-overall', $returnData);
    }

    protected function getEventCompetitionLabels(int $eventId): array
    {
        $competitionlabels = [];
        $eventCompetitions = EventCompetition::where('eventid', $eventId)
            ->orderBy('sequence')
            ->get(['eventcompetitionid', 'label', 'date']);

        foreach ($eventCompetitions as $competition) {
            $competitionlabels[$competition->eventcompetitionid] =
                $competition->label . ' - ' . date('d M', strtotime($competition->date));
        }

        return $competitionlabels;
    }

    protected function getEventOverallFlatScores(int $eventId): array
    {
        return DB::table('scores_flat as sf')
            ->join('evententrys as ee', 'sf.entryid', '=', 'ee.entryid')
            ->leftJoin('users as u', 'ee.userid', '=', 'u.userid')
            ->join('rounds as r', 'sf.roundid', '=', 'r.roundid')
            ->join('eventcompetitions as ec', 'sf.eventcompetitionid', '=', 'ec.eventcompetitionid')
            ->join('divisions as d', 'sf.divisionid', '=', 'd.divisionid')
            ->where('sf.eventid', $eventId)
            ->orderByDesc('sf.total')
            ->select([
                'sf.eventcompetitionid',
                'sf.total',
                'sf.inners',
                'sf.max',
                'ec.sequence',
                'ee.userid',
                'd.divisionid',
                'ee.firstname',
                'ee.lastname',
                'ee.gender',
                'u.username',
                'd.label as division',
                'd.bowtype',
                'r.label as roundname',
            ])
            ->get()
            ->all();
    }

    protected function buildEventOverallResults(array $flatscores, array $competitionlabels): array
    {
        $archerGroups = [];
        $competitionIds = array_keys($competitionlabels);

        foreach ($flatscores as $score) {
            $groupKey = $score->userid . ':' . $score->divisionid;

            if (empty($archerGroups[$groupKey])) {
                $row = ['archer' => $this->formatArcherLink($score), 'total' => 0, 'inners' => '', 'xcount' => ''];
                foreach ($competitionIds as $competitionId) {
                    $row[$competitionId] = '';
                }

                $archerGroups[$groupKey] = [
                    'bowtype' => $score->bowtype,
                    'divisionkey' => ($score->gender == 'm' ? 'Mens' : 'Womens') . ' ' . $score->division,
                    'rounds' => array_fill_keys($competitionIds, ''),
                    'row' => $row,
                    'lastsequence' => PHP_INT_MIN,
                ];
            }

            $archerGroups[$groupKey]['row'][$score->eventcompetitionid] = $score->total;
            $archerGroups[$groupKey]['row']['total'] += (int) $score->total;

            if ((int) $score->sequence >= $archerGroups[$groupKey]['lastsequence']) {
                $archerGroups[$groupKey]['row']['inners'] = $score->inners;
                $archerGroups[$groupKey]['row']['xcount'] = $score->max;
                $archerGroups[$groupKey]['lastsequence'] = (int) $score->sequence;
            }

            if (empty($archerGroups[$groupKey]['rounds'][$score->eventcompetitionid])) {
                $archerGroups[$groupKey]['rounds'][$score->eventcompetitionid] = $score->roundname;
            }
        }

        $final = [];
        foreach ($archerGroups as $group) {
            $bowtype = $group['bowtype'];
            $divisionkey = $group['divisionkey'];

            if (empty($final[$bowtype][$divisionkey]['rounds'])) {
                $final[$bowtype][$divisionkey]['rounds'] = $group['rounds'];
            } else {
                foreach ($group['rounds'] as $eventcompetitionid => $roundname) {
                    if (empty($final[$bowtype][$divisionkey]['rounds'][$eventcompetitionid])) {
                        $final[$bowtype][$divisionkey]['rounds'][$eventcompetitionid] = $roundname;
                    }
                }
            }

            $final[$bowtype][$divisionkey][] = $group['row'];
        }

        ksort($final);

        return $this->sortTotalOverallResults($final);
    }

    protected function formatArcherLink(object $score): string
    {
        return '<a href="/profile/public/'.$score->username.'">' .
            htmlentities(ucwords($score->firstname . ' ' . $score->lastname)) .
            '</a>';
    }

}
