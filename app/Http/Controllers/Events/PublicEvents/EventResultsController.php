<?php

namespace App\Http\Controllers\Events\PublicEvents;

use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\Score;
use Illuminate\Http\Request;


class EventResultsController extends EventController
{
    /**
     * Get the Events competitions and their results status
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function getEventResultsList(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->orderBy('date', 'asc')->get();

        $overall = false;
        foreach ($eventcompetitions as $eventcompetition) {

            $eventcompetition->score = Score::where('eventid', $eventcompetition->eventid)
                                            ->whereIn('roundid', json_decode($eventcompetition->roundids))
                                            ->get()
                                            ->first();

            if (!empty($eventcompetition->score)) {
                $overall = true;
            }

        }

        return view('events.results.eventcompetitions', compact('event', 'eventcompetitions', 'overall'));
    }


    /**
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function getEventCompetitionResults(Request $request)
    {
        if (empty($request->eventcompetitionid) || empty($request->eventurl)) {
            return back()->with('failure', 'Invalid Request');
        }

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (strcasecmp($request->eventcompetitionid, 'overall') === 0) {
            return $this->getEventOverallResults($event);
        }

        // Get the results for the event and the eventcompetitionid

        dd($request);
    }

    private function getEventOverallResults(Event $event)
    {
        return view('events.results.results');
        dd($event);
    }



}
