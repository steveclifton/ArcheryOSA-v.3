<?php

namespace App\Http\Controllers\Events\PublicEvents;

use App\Models\Division;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\FlatScore;
use App\Services\EventResultService;
use App\Services\LeagueResultsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ResultsController extends EventController
{

    /**
     * MAIN entry point into get results.
     *  - Does filtering between league and events
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getCompetitionResults(Request $request, LeagueResultsService $leagueResultsService, EventResultService $eventResultService)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event) || empty($request->eventcompetitionid) || empty($request->eventurl)) {
            return back()->with('failure', 'Invalid Request');
        }

        if (strcasecmp($request->eventcompetitionid, 'overall') === 0) {
            // league processing
            if ($event->isLeague()) {
                return $leagueResultsService->getLeagueOverallResults($event);
            }

            // Normal Event
            return $eventResultService->getEventOverallResults($event);
        }

        // league processing
        if ($event->isLeague()) {
            return $leagueResultsService->getLeagueCompetitionResults($event, $request->eventcompetitionid);
        }

        // Get the results for the event and the eventcompetitionid
        return $eventResultService->getEventCompetitionResults($event, $request->eventcompetitionid);

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

            if (empty($eventcompetition)) {
                return redirect('/');
            }

            $rangeArr = FlatScore::where('eventid', $eventcompetition->eventid)
                ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                ->whereBetween('week', [1, $eventcompetition->currentweek])
                ->pluck('week')
                ->unique()
                ->sort()
                ->values()
                ->toArray();

            return view('events.results.league.leaguecompetitions', compact('event', 'rangeArr', 'overall'));
        }


        // not a league
        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->orderBy('date', 'asc')->get();
        $haveScores = false;
        $scoresByCompetition = FlatScore::where('eventid', $event->eventid)
            ->whereIn('eventcompetitionid', $eventcompetitions->pluck('eventcompetitionid'))
            ->get()
            ->groupBy('eventcompetitionid');

        foreach ($eventcompetitions as $eventcompetition) {
            $eventcompetitionScores = $scoresByCompetition->get($eventcompetition->eventcompetitionid);
            $eventcompetition->score = $eventcompetitionScores ? $eventcompetitionScores->first() : null;

            if (!$haveScores && !empty($eventcompetition->score)) {
                $haveScores = true;
            }
        }

        // dont show overall if there are no results
        if ($overall && !$haveScores) {
            $overall = false;
        }
        return view('events.results.eventcompetitions', compact('event', 'eventcompetitions', 'overall'));
    }
}
