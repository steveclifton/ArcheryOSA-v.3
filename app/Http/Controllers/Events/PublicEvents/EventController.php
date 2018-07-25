<?php

namespace App\Http\Controllers\Events\PublicEvents;

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

    public function createEvent()
    {
        // Do some auth checking here. Can the user create an event?
        return view('events.auth.management.create');
    }

    public function getEventDetails(Request $request)
    {
        return view('events.public.details');
    }

    public function getEventRegistration(Request $request)
    {
        // Can they register for an event
        return view('events.public.registration');
    }

    public function getEventResults(Request $request)
    {
        return view('events.public.results');
    }

    public function getEventScoring(Request $request)
    {
        return view('events.public.scoring');
    }

    public function getPreviousEvents()
    {
        return view('events.completed.completed');
    }




}
