<?php

namespace App\Http\Controllers\Events\PublicEvents;


use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class EventController extends Controller
{

    /**
     * GET
     * Returns all the events that are open and able to be entered
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllEvents()
    {
        return view('events.public.open');
    }

    public function getEventDetails(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }
        return view('events.public.details', compact('event'));
    }

    public function getEventRegistration(Request $request)
    {
        // Can they register for an event
        return view('events.public.registration');
    }

    public function getEventResults(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }
        return view('events.public.results', compact('event'));
    }

    public function getEventScoring(Request $request)
    {
        return view('events.public.scoring');
    }

    public function getPreviousEvents()
    {
        return view('events.completed.events');
    }




}
