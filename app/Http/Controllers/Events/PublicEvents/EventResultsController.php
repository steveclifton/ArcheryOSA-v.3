<?php

namespace App\Http\Controllers\Events\PublicEvents;

use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\Score;
use Illuminate\Http\Request;


class EventResultsController extends EventController
{

    public function getEventResults(Request $request)
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


    public function getEventCompetitionResults(Request $request)
    {
        dd($request);
    }
}
