<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventPayment;
use Illuminate\Http\Request;

class EventPaymentsController extends Controller
{

    public function getEventPayments(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($event)) {
            return back()->with('failure', 'Cannot add at this stage');
        }

        $eventpayments = EventPayment::where('eventid', $event->eventid)->get();

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

        return view('events.auth.management.eventpayments', compact('eventpayments', 'event', 'eventcompetitions'));
    }

}
