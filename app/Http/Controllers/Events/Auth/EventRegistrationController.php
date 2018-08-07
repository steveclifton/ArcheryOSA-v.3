<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Requests\Auth\EventRegistration\CreateRegistration;
use App\Http\Requests\Auth\EventRegistration\UpdateRegistration;
use App\Models\Club;
use App\Models\Competition;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventEntry;
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

        $relations = UserRelation::where('userid', Auth::id())->pluck('relationid')->toarray();

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

        $user = User::where('username', $request->username ?? -1)->get()->first();

        if (empty($event) || empty($user)) {
            return back();
        }

        $eventcompetitions = DB::select("
            SELECT *
            FROM `eventcompetitions`
            WHERE `eventid` = :eventid
            AND `visible` = 1
        ", ['eventid' => $event->eventid]);


        $evententry = EventEntry::where('eventid', $event->eventid)
                                ->where('userid', $user->userid)
                                ->get()
                                ->first();

        $clubs = Club::where('visible', 1)->get();

        $divisionsfinal    = [];
        $competitionsfinal = [];
        foreach ($eventcompetitions as $eventcompetition) {


            $divisions = Division::wherein('divisionid', json_decode($eventcompetition->divisionids))->get();
            foreach ($divisions as $division) {
                $divisionsfinal[$division->divisionid] = $division;
            }

            $competitions = Competition::wherein('competitionid', json_decode($eventcompetition->competitionids))->get();

            foreach ($competitions as $competition) {
                $eventcompetition->competitions[] = $competition;
            }
            $competitionsfinal[$eventcompetition->date] = $eventcompetition;

        }

        // Means they need to create an event
        if (empty($evententry)) {
            return view('events.public.registration.createregistration',
                    compact('user', 'event', 'clubs', 'divisionsfinal', 'competitionsfinal'));
        }

        $entrycompetitions = EntryCompetition::where('entryid', $evententry->entryid)->get();

        $entrycompetitionids = [];
        foreach ($entrycompetitions as $entrycompetition) {
            $entrycompetitionids[$entrycompetition->eventcompetitionid][$entrycompetition->competitionid] = $entrycompetition->competitionid;
        }



        // Not empty, means they have entered the event already,
        return view('events.public.registration.updateregistration',
                compact('user', 'event', 'evententry', 'clubs', 'divisionsfinal', 'competitionsfinal', 'entrycompetitions', 'entrycompetitionids'));
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
                ->get()
                ->first();
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
        $evententry->divisionid    = !empty($validated['divisionid'])         ? intval($validated['divisionid'])         : '';
        $evententry->dateofbirth   = !empty($validated['dateofbirth'])    ? $validated['dateofbirth']            : '';
        $evententry->gender        = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->enteredby     = Auth::id();
        $evententry->hash          = $this->createHash();
        $evententry->save();


        // Get the competitionids for the entry
        $eventcompetitionids = !empty($validated['competitionids']) ? explode(',', $validated['competitionids']) : [];
        foreach ($eventcompetitionids as $competitionid) {

            @list($eventcompetitionid, $competitionid) = explode('-', $competitionid);

            if (empty($eventcompetitionid) || empty($competitionid)) {
                continue;
            }

            $entrycompetition = new EntryCompetition();
            $entrycompetition->entryid            = $evententry->entryid;
            $entrycompetition->eventid            = $event->eventid;
            $entrycompetition->eventcompetitionid = $eventcompetitionid;
            $entrycompetition->userid             = $validated['userid'];
            $entrycompetition->divisionid         = $validated['divisionid'];
            $entrycompetition->competitionid      = $competitionid;
            $entrycompetition->save();
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
                ->get()
                ->first();
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
        $evententry->divisionid   = !empty($validated['divisionid'])         ? intval($validated['divisionid'])         : '';
        $evententry->gender       = !empty($validated['gender'] == 'm')  ? 'm' : 'f';
        $evententry->dateofbirth  = !empty($validated['dateofbirth'])    ? $validated['dateofbirth'] : '';

        $evententry->save();

        $entrycompetitions = EntryCompetition::where('userid', $user->userid)
                                            ->where('entryid', $evententry->entryid)
                                            ->get();


        // Get the competitionids for the entry
        $eventcompetitionids = !empty($validated['competitionids']) ? explode(',', $validated['competitionids']) : [];
        foreach ($eventcompetitionids as $competitionid) {

            // explode out the ids
            @list($eventcompetitionid, $competitionid) = explode('-', $competitionid);

            if (empty($eventcompetitionid) || empty($competitionid)) {
                continue;
            }

            // try to get the entry that matches the ids
            $entrycompetition = EntryCompetition::where('eventcompetitionid', $eventcompetitionid)
                                                ->where('competitionid', $competitionid)
                                                ->get()
                                                ->first();

            // doesnt exist, create it
            if (empty($entrycompetition)) {
                $entrycompetition = new EntryCompetition();
                $entrycompetition->entryid            = $evententry->entryid;
                $entrycompetition->eventid            = $event->eventid;
                $entrycompetition->eventcompetitionid = $eventcompetitionid;
                $entrycompetition->userid             = $validated['userid'];
                $entrycompetition->divisionid         = $validated['divisionid'];
                $entrycompetition->competitionid      = $competitionid;
                $entrycompetition->save();
            }
            else {
                // It does exist, so remove it from the array
                foreach ($entrycompetitions as $key => $ec) {

                    $entrycomp = $ec->eventcompetitionid == $entrycompetition->eventcompetitionid;
                    $comp      = $ec->competitionid      == $entrycompetition->competitionid;

                    if ($entrycomp && $comp) {
                        unset($entrycompetitions[$key]);
                    }
                }
            }
        } // foreach

        // if there is still stuff left in the collection, it means they have been unticked by the user
        if (!empty($entrycompetitions)) {
            foreach ($entrycompetitions as $ec) {
                $ec->delete();
            }
        }



        return redirect('/event/register/' . $event->eventurl)->with('success', 'Entry Updated!');
    }

}
