<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function getAllEvents()
    {
        // get all the events the user can manage
        $events = DB::select("
            SELECT *
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            WHERE `ea`.`userid` = :userid
        ", ['userid' => Auth::id()]);
        
        return view('events.auth.events');
    }

    public function getEventView()
    {
        return view('events.auth.manage');
    }
}
