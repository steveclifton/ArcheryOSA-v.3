<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEntryConfirmation;
use App\Models\Club;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventEntry;
use App\Models\Round;
use App\User;
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
            SELECT ee.entryid, u.username, CONCAT_WS(' ', ee.firstname, ee.lastname ) as name, ee.confirmationemail, ee.paid, d.label as division, ee.created_at as created, es.label as status  
            FROM `evententrys` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            JOIN `entrystatus` es USING (`entrystatusid`)
            WHERE `eventid` = '".$event->eventid."'
        ");

        return view('events.auth.management.entries', compact('event', 'evententries'));
    }


    public function getEventEntryAddView(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Cannot add at this stage');
        }

        $eventcompetitions = DB::select("
            SELECT *
            FROM `eventcompetitions`
            WHERE `eventid` = :eventid
        ", ['eventid' => $event->eventid]);

        $leaguecompround = null;
        if ($event->isLeague()) {
            $leaguecompround = reset($eventcompetitions);
            $leaguecompround = $leaguecompround->eventcompetitionid . '-' . $leaguecompround->roundids;
        }

        $multipledivisions = false;
        $divisionsfinal    = [];
        $competitionsfinal = [];
        foreach ($eventcompetitions as $eventcompetition) {

            if (empty($multipledivisions) && $eventcompetition->multipledivisions) {
                $multipledivisions = true;
            }

            $divisions = Division::wherein('divisionid', json_decode($eventcompetition->divisionids))->orderBy('bowtype')->get();
            foreach ($divisions as $division) {
                $divisionsfinal[$division->divisionid] = $division;
            }

            if ($event->isLeague()) {
                $eventcompetition->rounds = Round::where('roundid', $eventcompetition->roundids)->get();
            }
            else {
                $eventcompetition->rounds = Round::wherein('roundid', json_decode($eventcompetition->roundids))->get();
            }

            $competitionsfinal[$eventcompetition->date] = $eventcompetition;
        }

        $clubs = Club::where('visible', 1)->orderby('label')->get();


        return view('events.auth.management.entries.add',
            compact('user', 'event', 'clubs', 'divisionsfinal', 'competitionsfinal', 'leaguecompround', 'multipledivisions'));
    }

    public function getEventEntryUpdateView(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        $user = User::where('username', $request->username)->get()->first();

        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Cannot find user');
        }


        $evententry = EventEntry::where('eventid', $event->eventid ?? -1)
                        ->where('userid', $user->userid)
                        ->get()
                        ->first();


        $eventcompetitions = DB::select("
            SELECT *
            FROM `eventcompetitions`
            WHERE `eventid` = :eventid
        ", ['eventid' => $event->eventid]);

        $leaguecompround = null;
        if ($event->isLeague()) {
            $leaguecompround = reset($eventcompetitions);
            $leaguecompround = $leaguecompround->eventcompetitionid . '-' . $leaguecompround->roundids;
        }

        $multipledivisions = false;
        $divisionsfinal    = [];
        $competitionsfinal = [];
        foreach ($eventcompetitions as $eventcompetition) {

            if (empty($multipledivisions) && $eventcompetition->multipledivisions) {
                $multipledivisions = true;
            }

            $divisions = Division::wherein('divisionid', json_decode($eventcompetition->divisionids))->orderBy('bowtype')->get();
            foreach ($divisions as $division) {
                $divisionsfinal[$division->divisionid] = $division;
            }

            if ($event->isLeague()) {
                $eventcompetition->rounds = Round::where('roundid', $eventcompetition->roundids)->get();
            }
            else {
                $eventcompetition->rounds = Round::wherein('roundid', json_decode($eventcompetition->roundids))->get();
            }

            $competitionsfinal[$eventcompetition->date] = $eventcompetition;
        }

        $clubs = Club::where('visible', 1)->orderby('label')->get();

        $entrycompetitions = EntryCompetition::where('entryid', $evententry->entryid)->get();

        $entrycompetitionids = [];
        foreach ($entrycompetitions as $entrycompetition) {
            $entrycompetitionids[$entrycompetition->eventcompetitionid][$entrycompetition->roundid] = $entrycompetition->roundid;
        }


        return view('events.auth.management.entries.update',
                compact('user', 'entrycompetitionids', 'evententry', 'event',
                        'clubs', 'divisionsfinal', 'competitionsfinal',
                        'leaguecompround', 'multipledivisions'
                        )
                    );
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
