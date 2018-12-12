<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEntryConfirmation;
use App\Jobs\SendEventUpdate;
use App\Models\Club;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventEntry;
use App\Models\FlatScore;
use App\Models\Round;
use App\Models\Score;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventEntryController extends EventController
{

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($this->event)) {
            return back()->with('failure', 'Invalid Event');
        }

    }

    public function getEventEntriesView(Request $request)
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

        $evententries = DB::select("
            SELECT ee.entryid, u.username, CONCAT_WS(' ', ee.firstname, ee.lastname ) as name, ee.confirmationemail, 
                  ee.paid, ee.notes, d.label as division, ee.created_at as created, es.label as status  
            FROM `evententrys` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            JOIN `entrystatus` es USING (`entrystatusid`)
            WHERE `eventid` = '".$event->eventid."'
        ");

        $canremoveentry = false;
        if (time() < strtotime($event->start)){
            $canremoveentry = true;
        }

        return view('events.auth.management.entries', compact('event', 'evententries', 'canremoveentry'));
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

        $multipledivisions = $event->multipledivisions;
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

            $competitionsfinal[$eventcompetition->date][$eventcompetition->label] = $eventcompetition;
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

        $multipledivisions = $event->multipledivisions;
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

            $competitionsfinal[$eventcompetition->date][$eventcompetition->label] = $eventcompetition;
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

    public function getEventEntryEmailView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        $user = User::where('username', $request->username)->get()->first();

        $evententry = EventEntry::where('eventid', $event->eventid ?? -1)
            ->where('userid', $user->userid)
            ->get()
            ->first();

        if (empty($event) || empty($user) || empty($evententry)) {
            return back()->with('failure', 'Cannot perform request');
        }

        return view('events.auth.management.entries.email',
            compact('user', 'event'));
    }


    /**********************
     * POST
     **********************/
    public function sendEventEntryEmail(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }


        $evententry = EventEntry::where('eventid', $event->eventid ?? -1)
                                ->where('userid', $request->input('userid'))
                                ->get()
                                ->first();

        if (empty($evententry)) {
            return redirect()->route('home');
        }

        SendEventUpdate::dispatch($evententry->email, $event->label, $request->input('message'), $event->contactname, $event->email);

        return redirect('events/manage/evententries/' . $event->eventurl)->with('success', 'Email Sent');

    }



    /**********************
     * AJAX
     **********************/
    public function approveEntry(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
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
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
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
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
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

        SendEntryConfirmation::dispatch($entry->email, $entry->firstname, $this->event->label, route('event', $this->event->eventurl));

        // Set it to pending if not entered
        $entry->confirmationemail = 1;
        $entry->save();

        return response()->json([
            'success' => true
        ]);


    }



    public function removeEntry(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
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

        EntryCompetition::where('entryid', $entry->entryid)->delete();
        Score::where('entryid', $entry->entryid)->delete();
        FlatScore::where('entryid', $entry->entryid)->delete();

        $entry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Entry Removed'
        ]);

    }




}
