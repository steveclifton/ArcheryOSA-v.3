<?php

namespace App\Http\Controllers\Vue\Admin\Events;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function getAllEvents()
    {

        // check to see they are auth level first
        if (!Auth::user()->isEventAdmin()) {
            return abort(403);
        }

        if (Auth::user()->isSuperAdmin()) {
            return DB::select("
                SELECT e.eventid, e.start, e.end, e.label, e.status, e.visible, count(ee.entryid) as entries, e.eventurl
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                GROUP BY `e`.`eventid`
                ORDER BY `e`.`start` DESC
            ");
        }

        return DB::select("
                SELECT e.eventid, e.start, e.end, e.label, e.status, e.visible, count(ee.entryid) as entries, e.eventurl
                FROM `events` e
                JOIN `evententrys` ee USING (`eventid`)
                JOIN `eventadmins` ea USING (`eventid`)
                WHERE `ea`.`userid` = :userid
                GROUP BY `e`.`eventid`
                ORDER BY `e`.`start` DESC
        ", ['userid' => Auth::id()]);

    }

    public function getEventDetails(Request $request)
    {
        if (empty($request->eventUrl)) {
            return abort(400);
        }

        $event = Event::where('eventurl', $request->eventUrl)->first();

        if (empty($event)) {
            return abort(404);
        }

        $return = new \stdClass;

        $return->event = [

        ];


        dd();
    }
}
