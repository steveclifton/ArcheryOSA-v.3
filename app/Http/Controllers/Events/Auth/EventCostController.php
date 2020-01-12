<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventPayment;
use Illuminate\Http\Request;

class EventCostController extends Controller
{
    public function getEventCosts(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            return back()->with('failure', 'Cannot add at this stage');
        }

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

        return view('events.auth.management.eventcosts', compact('event', 'eventcompetitions'));
    }

    public function updateEventCost(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            return back()->with('failure', 'Cannot add at this stage');
        }

        if ($request->id == 'total') {
            $event->totalcost = $request->cost;
            $event->save();

            return response()->json([
                'success' => true,
                'data'    => []
            ]);
        }

        $eventcompetition = EventCompetition::where('eventcompetitionid', $request->id)->first();

        if (!empty($eventcompetition)) {
            $eventcompetition->cost = $request->cost;
            $eventcompetition->save();

            return response()->json([
                'success' => true,
                'data'    => []
            ]);
        }
    }



}
