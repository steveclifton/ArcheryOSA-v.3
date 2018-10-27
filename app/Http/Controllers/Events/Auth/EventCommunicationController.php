<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEventUpdate;
use App\Models\EventEntry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventCommunicationController extends EventController
{

    public function getEventCommView(Request $request)
    {
        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
        }
        else {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);
        }

        $event = !empty($event) ? reset($event) : null;

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        return view('events.auth.management.emails', compact('event'));
    }


    /**
     * POST
     */

    public function sendEventEmail(Request $request)
    {
        // Get Event
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
        }
        else {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);
        }

        $event = !empty($event) ? reset($event) : null;

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        $type = $request->input('email');
        $message = $request->input('message');

        if (empty($type) || empty($message)) {
            return back()->with('failure', 'Please check details and try again');
        }

        switch($type) {

            case 'all' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->get();

                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'));
                }
                break;

            case 'approved' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->where('entrystatusid', 2)->get();
                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'));
                }
                break;

            case 'topay' :
                $evententrys = EventEntry::where('eventid', $event->eventid)->where('paid', 0)->get();

                foreach ($evententrys as $evententry) {
                    SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'));
                }
                break;

            default:
                return back()->with('failure', 'Please check details and try again');
                break;
        }

        return redirect('events/manage/' . $event->eventurl)->with('success', 'Email Sent');

    }




}
