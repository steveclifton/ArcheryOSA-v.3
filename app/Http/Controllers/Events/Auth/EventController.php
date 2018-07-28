<?php

namespace App\Http\Controllers\Events\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EventController extends Controller
{
    public function getAllEvents()
    {
        // get all the events the user can manage
        return view('events.auth.events');
    }

    public function getEventView()
    {
        return view('events.auth.manage');
    }
}
