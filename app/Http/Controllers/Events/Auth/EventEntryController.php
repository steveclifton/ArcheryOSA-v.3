<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEntryConfirmation;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventEntryController extends EventController
{

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($this->event)) {
            return back()->with('failure', 'Invalid');
        }

        $eventadmin = EventAdmin::where('eventid', $this->event->eventid)->get()->first();

        if (empty($eventadmin)) {
            return back()->with('failure', 'Invalid');
        }
    }



    public function getEventEntriesView()
    {
        $event = $this->event;

        $evententries = DB::select("
            SELECT ee.entryid, CONCAT_WS(' ', ee.firstname, ee.lastname ) as name, ee.confirmationemail, ee.paid, d.label as division, ee.created_at as created, es.label as status  
            FROM `evententrys` ee
            JOIN `divisions` d USING (`divisionid`)
            JOIN `entrystatus` es USING (`entrystatusid`)
            WHERE `eventid` = '".$event->eventid."'
        ");

        return view('events.auth.management.entries', compact('event', 'evententries'));
    }






    /**********************
     * AJAX
     */


    public function approveEntry(Request $request)
    {
        if (empty($request->eventurl)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $entryid = $request->entryid;

        $entry = EventEntry::where('eventid', $this->event->eventid)
                            ->where('entryid', $entryid)
                            ->get()
                            ->first();

        if (empty($entry)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }


        // Set it to pending if not entered
        if ($entry->entrystatusid == 2) {
            $entry->entrystatusid = 1;
            $message = 'Pending';
        }
        else {
            $entry->entrystatusid = 2;
            $message = 'Entered';
        }
        $entry->save();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);


    }

    public function approvePaid(Request $request)
    {
        if (empty($request->eventurl)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $entryid = $request->entryid;

        $entry = EventEntry::where('eventid', $this->event->eventid)
            ->where('entryid', $entryid)
            ->get()
            ->first();

        if (empty($entry)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }


        // Set it to pending if not entered
        if ($entry->paid == 1) {
            $entry->paid = 0;
            $message = '';
        }
        else {
            $entry->paid = 1;
            $message = '<i class="fa fa-check"></i>';
        }
        $entry->save();

        return response()->json([
            'success' => true,
            'message' => $message
        ]);


    }

    public function sendApprove(Request $request)
    {
        if (empty($request->eventurl)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $entryid = $request->entryid;

        $entry = EventEntry::where('eventid', $this->event->eventid)
            ->where('entryid', $entryid)
            ->where('confirmationemail', 0)
            ->get()
            ->first();

        if (empty($entry)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        SendEntryConfirmation::dispatch($entry->email, $entry->firstname, $this->event->label, makeEventDetailsUrl($this->event->eventurl));

        // Set it to pending if not entered
        $entry->confirmationemail = 1;
        $entry->save();

        return response()->json([
            'success' => true

        ]);


    }

}
