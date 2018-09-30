<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Requests\Auth\EventRegistration\CreateRegistration;
use App\Http\Requests\Auth\EventRegistration\UpdateRegistration;
use App\Jobs\SendEntryReceived;
use App\Jobs\SendEventAdminEntryReceived;
use App\Models\Club;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventEntry;
use App\Models\Round;
use App\Models\UserRelation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class EventRegistrationController extends EventController
{



    public function getRegistrationList(Request $request)
    {
        // Get the event
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        // Try get an existing entry | redirect if exists
        $evententry = EventEntry::where('eventid', $event->eventid)->get()->first();

        $relations = UserRelation::where('userid', Auth::id())->where('authorised', 1)->pluck('relationid')->toarray();

        if (!empty($relations)) {
            $relations = User::wherein('userid', $relations)->get();
        }

        // Can they register for an event
        return view('events.public.registration.registrationlist',
                    compact('event', 'evententry', 'relations')
        );
    }


    public function getRegistration(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        $user  = User::where('username', $request->username ?? -1)->get()->first();

        if (empty($event) || empty($user)) {
            return back();
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



        $clubs = Club::where('visible', 1)->get();

        $evententry = EventEntry::where('eventid', $event->eventid)
            ->where('userid', $user->userid)
            ->get()
            ->first();

        // Means they need to create an event
        if (empty($evententry)) {
            return view('events.public.registration.createregistration',
                    compact('user', 'event', 'clubs', 'divisionsfinal', 'competitionsfinal', 'leaguecompround', 'multipledivisions'));
        }

        $entrycompetitions = EntryCompetition::where('entryid', $evententry->entryid)->get();

        $entrycompetitionids = [];
        foreach ($entrycompetitions as $entrycompetition) {
            $entrycompetitionids[$entrycompetition->eventcompetitionid][$entrycompetition->roundid] = $entrycompetition->roundid;
        }

        $divisions = [];
        if ($event->isLeague()) {
            $divisions = explode(',',$evententry->divisionid);
        }




        // Not empty, means they have entered the event already,
        return view('events.public.registration.updateregistration',
                compact('user', 'event', 'evententry', 'clubs', 'divisionsfinal', 'competitionsfinal',
                    'entrycompetitions', 'entrycompetitionids', 'leaguecompround', 'multipledivisions', 'divisions'));
    }



    public function createRegistration(CreateRegistration $request)
    {
        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'])->get()->first();

        $user = Auth::user();

        if ($validated['userid'] != $user->userid) {
            // make sure the person logged in can enter the person
            $user = UserRelation::where('userid', Auth::id())
                ->where('relationid', $validated['userid'])
                ->where('authorised', 1)
                ->get()
                ->first();

            if (!empty($user)) {
                $user = User::where('userid', $validated['userid'])->get()->first();
            }
        }

        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Please try again later');
        }






        // Store the single event entry
        $evententry = new EventEntry();

        $evententry->userid        = $validated['userid'];
        $evententry->eventid       = $event->eventid;
        $evententry->entrystatusid = 1; // 1 is pending
        $evententry->paid          = 0; // 0 is not paid yet
        $evententry->firstname     = !empty($validated['firstname'])      ? strtolower($validated['firstname'])  : '';
        $evententry->lastname      = !empty($validated['lastname'])       ? strtolower($validated['lastname'])   : '';
        $evententry->email         = !empty($validated['email'])          ? $validated['email']                  : '';
        $evententry->address       = !empty($validated['address'])        ? strtolower($validated['address'])    : '';
        $evententry->phone         = !empty($validated['phone'])          ? strtolower($validated['phone'])      : '';
        $evententry->membership    = !empty($validated['membership'])     ? strtolower($validated['membership']) : '';
        $evententry->notes         = !empty($validated['notes'])          ? strtolower($validated['notes'])      : '';
        $evententry->clubid        = !empty($validated['clubid'])         ? intval($validated['clubid'])         : '';
        $evententry->divisionid    = !empty($validated['divisionid'])     ? $validated['divisionid']             : '';
        $evententry->dateofbirth   = !empty($validated['dateofbirth'])    ? $validated['dateofbirth']            : '';
        $evententry->gender        = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->enteredby     = Auth::id();
        $evententry->hash          = $this->createHash();
        $evententry->save();


        $divisionids = explode(',', $validated['divisionid']);

        foreach ($divisionids as $divisionid) {

            // Get the competitionids for the entry
            $eventcompetitionids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
            foreach ($eventcompetitionids as $competitionid) {

                @list($eventcompetitionid, $roundid) = explode('-', $competitionid);
                if (empty($eventcompetitionid) || empty($roundid)) {
                    continue;
                }


                $entrycompetition = new EntryCompetition();
                $entrycompetition->entryid            = $evententry->entryid;
                $entrycompetition->eventid            = $event->eventid;
                $entrycompetition->eventcompetitionid = $eventcompetitionid;
                $entrycompetition->userid             = $validated['userid'];
                $entrycompetition->divisionid         = $divisionid;
                $entrycompetition->competitionid      = '';
                $entrycompetition->roundid            = $roundid;

                $entrycompetition->save();
            }

        }

        SendEntryReceived::dispatch($evententry->email, $event->label);

        if ($event->adminnotifications) {
            SendEventAdminEntryReceived::dispatch($event->email, $event->label, $validated['email']);
        }

        return redirect('/event/register/' . $event->eventurl)->with('success', 'Entry Received!');
    }

    public function updateRegistration(UpdateRegistration $request)
    {
        $validated = $request->validated();


        $event = Event::where('eventid', $validated['eventid'])->get()->first();

        $user = Auth::user();

        if ($validated['userid'] != $user->userid) {
            // make sure the person logged in can enter the person
            $user = UserRelation::where('userid', Auth::id())
                ->where('relationid', $validated['userid'])
                ->where('authorised', 1)
                ->get()
                ->first();

            if (!empty($user)) {
                $user = User::where('userid', $validated['userid'])->get()->first();
            }
        }

        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Please try again later');
        }



        // Store the single event entry
        $evententry = EventEntry::where('userid', $user->userid)
                                ->where('eventid', $event->eventid)
                                ->get()
                                ->first();


        $evententry->userid       = $validated['userid'];
        $evententry->eventid      = $event->eventid;
        $evententry->firstname    = !empty($validated['firstname'])      ? strtolower($validated['firstname'])   : '';
        $evententry->lastname     = !empty($validated['lastname'])       ? strtolower($validated['lastname'])    : '';
        $evententry->email        = !empty($validated['email'])          ? $validated['email']                   : '';
        $evententry->address      = !empty($validated['address'])        ? strtolower($validated['address'])     : '';
        $evententry->phone        = !empty($validated['phone'])          ? strtolower($validated['phone'])       : '';
        $evententry->membership   = !empty($validated['membership'])     ? strtolower($validated['membership'])  : '';
        $evententry->notes        = !empty($validated['notes'])          ? strtolower($validated['notes'])       : '';
        $evententry->clubid       = !empty($validated['clubid'])         ? intval($validated['clubid'])          : '';
        $evententry->divisionid   = !empty($validated['divisionid'])     ? $validated['divisionid']              : '';
        $evententry->gender       = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->dateofbirth  = !empty($validated['dateofbirth'])    ? $validated['dateofbirth'] : '';

        $evententry->save();

        $entrycompetitions = EntryCompetition::where('userid', $user->userid)
                                            ->where('entryid', $evententry->entryid)
                                            ->get();


        $divisionids = explode(',', $validated['divisionid']);


        foreach ($divisionids as $divisionid) {
            // Get the competitionids for the entry
            $eventcompetitionids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
            foreach ($eventcompetitionids as $competitionid) {

                // explode out the ids
                @list($eventcompetitionid, $roundid) = explode('-', $competitionid);

                if (empty($eventcompetitionid) || empty($roundid)) {
                    continue;
                }

                // try to get the entry that matches the ids
                $entrycompetition = EntryCompetition::where('eventcompetitionid', $eventcompetitionid)
                    ->where('roundid', $roundid)
                    ->where('divisionid', $divisionid)
                    ->where('userid', $user->userid)
                    ->get()
                    ->first();

                // doesnt exist, create it
                if (empty($entrycompetition)) {
                    $entrycompetition = new EntryCompetition();
                    $entrycompetition->entryid            = $evententry->entryid;
                    $entrycompetition->eventid            = $event->eventid;
                    $entrycompetition->eventcompetitionid = $eventcompetitionid;
                    $entrycompetition->userid             = $validated['userid'];
                    $entrycompetition->divisionid         = $divisionid;
                    $entrycompetition->competitionid      = '';
                    $entrycompetition->roundid            = $roundid;
                    $entrycompetition->save();
                }
                else {

                    // It does exist, so remove it from the array
                    foreach ($entrycompetitions as $key => $ec) {

                        $entrycomp = $ec->eventcompetitionid == $entrycompetition->eventcompetitionid;

                        $roundid   = $ec->roundid            == $entrycompetition->roundid;

                        if ($entrycomp && $roundid) {
                            unset($entrycompetitions[$key]);
                        }
                    }
                }
            } // foreach
        }

        // if there is still stuff left in the collection, it means they have been unticked by the user
        if (!empty($entrycompetitions)) {
            foreach ($entrycompetitions as $ec) {
                $ec->delete();
            }
        }



        return redirect('/event/register/' . $event->eventurl)->with('success', 'Entry Updated!');
    }


    public function createAdminRegistration(CreateRegistration $request)
    {
        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'])->get()->first();

        $user = User::where('userid', $validated['userid'])->get()->first();

        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Please try again later');
        }
        else if (!empty($user->email != $validated['email'])) {
            return back()->with('failure', 'Email does not match that on record');
        }

        $evententry = EventEntry::where('userid', $user->userid)
            ->where('eventid', $event->eventid)
            ->get()
            ->first();

        if (!empty($evententry)) {
            return back()->with('failure', 'Entry already exists');
        }


        if (empty($evententry)) {
            $evententry = new EventEntry();
        }


        $evententry->userid        = $validated['userid'];
        $evententry->eventid       = $event->eventid;
        $evententry->entrystatusid = 1; // 1 is pending
        $evententry->paid          = 0; // 0 is not paid yet
        $evententry->firstname     = !empty($validated['firstname'])      ? strtolower($validated['firstname'])  : '';
        $evententry->lastname      = !empty($validated['lastname'])       ? strtolower($validated['lastname'])   : '';
        $evententry->email         = !empty($validated['email'])          ? $validated['email']                  : '';
        $evententry->address       = !empty($validated['address'])        ? strtolower($validated['address'])    : '';
        $evententry->phone         = !empty($validated['phone'])          ? strtolower($validated['phone'])      : '';
        $evententry->membership    = !empty($validated['membership'])     ? strtolower($validated['membership']) : '';
        $evententry->notes         = !empty($validated['notes'])          ? strtolower($validated['notes'])      : '';
        $evententry->clubid        = !empty($validated['clubid'])         ? intval($validated['clubid'])         : '';
        $evententry->divisionid    = !empty($validated['divisionid'])     ? $validated['divisionid']             : '';
        $evententry->dateofbirth   = !empty($validated['dateofbirth'])    ? $validated['dateofbirth']            : '';
        $evententry->gender        = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->enteredby     = Auth::id();
        $evententry->hash          = $this->createHash();
        $evententry->save();


        $divisionids = explode(',', $validated['divisionid']);

        foreach ($divisionids as $divisionid) {

            // Get the competitionids for the entry
            $eventcompetitionids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
            foreach ($eventcompetitionids as $competitionid) {

                @list($eventcompetitionid, $roundid) = explode('-', $competitionid);
                if (empty($eventcompetitionid) || empty($roundid)) {
                    continue;
                }


                $entrycompetition = new EntryCompetition();
                $entrycompetition->entryid            = $evententry->entryid;
                $entrycompetition->eventid            = $event->eventid;
                $entrycompetition->eventcompetitionid = $eventcompetitionid;
                $entrycompetition->userid             = $validated['userid'];
                $entrycompetition->divisionid         = $divisionid;
                $entrycompetition->competitionid      = '';
                $entrycompetition->roundid            = $roundid;

                $entrycompetition->save();
            }

        }

        SendEntryReceived::dispatch($evententry->email, $event->label);

        return redirect('/events/manage/evententries/' . $event->eventurl)->with('success', 'Entry Added!');

    }

    public function updateAdminRegistration(UpdateRegistration $request)
    {
        $validated = $request->validated();


        $event = Event::where('eventid', $validated['eventid'])->get()->first();

        $user = User::where('userid', $validated['userid'])->get()->first();


        if (empty($event) || empty($user)) {
            return back()->with('failure', 'Please try again later');
        }

        // Store the single event entry
        $evententry = EventEntry::where('userid', $user->userid)
            ->where('eventid', $event->eventid)
            ->get()
            ->first();


        $evententry->userid       = $validated['userid'];
        $evententry->eventid      = $event->eventid;
        $evententry->firstname    = !empty($validated['firstname'])      ? strtolower($validated['firstname'])   : '';
        $evententry->lastname     = !empty($validated['lastname'])       ? strtolower($validated['lastname'])    : '';
        $evententry->email        = !empty($validated['email'])          ? $validated['email']                   : '';
        $evententry->address      = !empty($validated['address'])        ? strtolower($validated['address'])     : '';
        $evententry->phone        = !empty($validated['phone'])          ? strtolower($validated['phone'])       : '';
        $evententry->membership   = !empty($validated['membership'])     ? strtolower($validated['membership'])  : '';
        $evententry->notes        = !empty($validated['notes'])          ? strtolower($validated['notes'])       : '';
        $evententry->clubid       = !empty($validated['clubid'])         ? intval($validated['clubid'])          : '';
        $evententry->divisionid   = !empty($validated['divisionid'])     ? $validated['divisionid']              : '';
        $evententry->gender       = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->dateofbirth  = !empty($validated['dateofbirth'])    ? $validated['dateofbirth'] : '';

        $evententry->save();

        $entrycompetitions = EntryCompetition::where('userid', $user->userid)
            ->where('entryid', $evententry->entryid)
            ->get();


        $divisionids = explode(',', $validated['divisionid']);


        foreach ($divisionids as $divisionid) {
            // Get the competitionids for the entry
            $eventcompetitionids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
            foreach ($eventcompetitionids as $competitionid) {

                // explode out the ids
                @list($eventcompetitionid, $roundid) = explode('-', $competitionid);

                if (empty($eventcompetitionid) || empty($roundid)) {
                    continue;
                }

                // try to get the entry that matches the ids
                $entrycompetition = EntryCompetition::where('eventcompetitionid', $eventcompetitionid)
                    ->where('roundid', $roundid)
                    ->where('divisionid', $divisionid)
                    ->where('userid', $user->userid)
                    ->get()
                    ->first();

                // doesnt exist, create it
                if (empty($entrycompetition)) {
                    $entrycompetition = new EntryCompetition();
                    $entrycompetition->entryid            = $evententry->entryid;
                    $entrycompetition->eventid            = $event->eventid;
                    $entrycompetition->eventcompetitionid = $eventcompetitionid;
                    $entrycompetition->userid             = $validated['userid'];
                    $entrycompetition->divisionid         = $divisionid;
                    $entrycompetition->competitionid      = '';
                    $entrycompetition->roundid            = $roundid;
                    $entrycompetition->save();
                }
                else {

                    // It does exist, so remove it from the array
                    foreach ($entrycompetitions as $key => $ec) {

                        $entrycomp = $ec->eventcompetitionid == $entrycompetition->eventcompetitionid;

                        $roundid   = $ec->roundid            == $entrycompetition->roundid;

                        if ($entrycomp && $roundid) {
                            unset($entrycompetitions[$key]);
                        }
                    }
                }
            } // foreach
        }

        // if there is still stuff left in the collection, it means they have been unticked by the user
        if (!empty($entrycompetitions)) {
            foreach ($entrycompetitions as $ec) {
                $ec->delete();
            }
        }



        return back()->with('success', 'Entry Updated!');
    }

}
