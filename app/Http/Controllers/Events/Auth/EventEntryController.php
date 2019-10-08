<?php

namespace App\Http\Controllers\Events\Auth;

use App\Jobs\SendEntryConfirmation;
use App\Jobs\SendEventUpdate;
use App\Models\Club;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventEntry;
use App\Models\FlatScore;
use App\Models\Round;
use App\Models\School;
use App\Models\Score;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Webpatser\Countries\Countries;

class EventEntryController extends EventController
{

    public function __construct(Request $request)
    {
        parent::__construct();

        $this->event = Event::where('eventurl', $request->eventurl)->first();

        if (empty($this->event)) {
            return back()->with('failure', 'Invalid Event');
        }

    }

    public function getEventEntriesView(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        $evententries = DB::select("
            SELECT ee.entryid, u.username, CONCAT_WS(' ', ee.firstname, ee.lastname ) as name, ee.confirmationemail, 
                  ee.paid, ee.notes, d.label as division, ee.created_at as created, es.label as status, ee.pickup 
            FROM `evententrys` ee
            JOIN `users` u USING (`userid`)
            JOIN `divisions` d USING (`divisionid`)
            JOIN `entrystatus` es USING (`entrystatusid`)
            WHERE `eventid` = '".(int)$event->eventid."'
        ");

        $canremoveentry = true;

        return view('events.auth.management.entries', compact('event', 'evententries', 'canremoveentry'));
    }

    public function getEventEntryAddView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->first();

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

        $divisionsfinal    = [];
        $competitionsfinal = [];
        foreach ($eventcompetitions as $eventcompetition) {

            $eventcompetition->divisioncomplete = $divisions = Division::wherein('divisionid', json_decode($eventcompetition->divisionids))->orderBy('bowtype')->get();

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

            $eventcomps[] = $eventcompetition;
        }

        $clubs = Club::where('visible', 1)->orderby('label')->get();

        $schools = null;
        if ($event->schoolrequired) {
            $schools = School::where('visible', 1)->orderby('label')->get();
        }

        $countrys = Countries::all();

        return view('events.auth.management.entries.add',
            compact('event', 'schools', 'clubs', 'divisionsfinal', 'competitionsfinal',
                'leaguecompround', 'eventcomps', 'countrys'));
    }

    public function getEventEntryUpdateView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->first();

        $user = User::where('username', $request->username)->first();

        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Cannot find user');
        }


        $evententry = EventEntry::where('eventid', $event->eventid ?? -1)
                        ->where('userid', $user->userid)
                        ->first();


        $eventcompetitions = DB::select("
            SELECT *
            FROM `eventcompetitions`
            WHERE `eventid` = :eventid
            ORDER BY `date` ASC
        ", ['eventid' => $event->eventid]);


        if (empty($eventcompetitions)) {
            return back()->with('failure', 'Unable to get entry form');
        }


        $divisionsfinal = [];
        foreach ($eventcompetitions as $eventcompetition) {
            $eventcompetition->divisioncomplete = $divisions = Division::wherein('divisionid', json_decode($eventcompetition->divisionids))->orderBy('bowtype')->get();

            foreach ($divisions as $division) {
                $divisionsfinal[$division->divisionid] = $division;
            }

            if ($event->isLeague()) {
                $eventcompetition->rounds = Round::where('roundid', $eventcompetition->roundids)->get();
            }
            else {
                $eventcompetition->rounds = Round::wherein('roundid', json_decode($eventcompetition->roundids))->get();
            }

            $eventcomps[] = $eventcompetition;
        }


        // Get an array of the users entry divisions
        $userentrydivisions = [];
        $userentryrounds = [];
        foreach ($evententry->entrycompetitions() as $entrycomp) {

            if ($event->isleague()) {
                $userentrydivisions[] = $entrycomp->divisionid;
                continue;
            }
            $userentrydivisions[$entrycomp->eventcompetitionid] = $entrycomp->divisionid;
            $userentryrounds[$entrycomp->eventcompetitionid] = $entrycomp->roundid;
        }

        $clubs = Club::where('visible', 1)->orderby('label')->get();

        $schools = null;
        if ($event->schoolrequired) {
            $schools = School::where('visible', 1)->orderby('label')->get();
        }
        $countrys = Countries::all();

        return view('events.auth.management.entries.update',
                compact('user', 'evententry', 'event', 'schools',
                        'clubs', 'divisionsfinal', 'countrys', 'eventcomps','userentrydivisions', 'userentryrounds')
                    );
    }

    public function getEventEntryEmailView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->first();

        $user = User::where('username', $request->username)->first();

        $evententry = EventEntry::where('eventid', $event->eventid ?? -1)
            ->where('userid', $user->userid)
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
