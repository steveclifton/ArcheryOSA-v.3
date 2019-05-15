<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Requests\Auth\EventRegistration\CreateRegistration;
use App\Http\Requests\Auth\EventRegistration\UpdateRegistration;
use App\Jobs\SendEntryReceived;
use App\Jobs\SendEventAdminEntryReceived;
use App\Models\Club;
use App\Models\Division;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventEntry;
use App\Models\Round;
use App\Models\School;
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

        if ($event->isEvent() && !$event->canEnterEvent()) {
            return redirect('/event/details/' . $event->eventurl)->with('failure', 'Event entrys are closed');
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

        if ($event->isEvent() && !$event->canEnterEvent()) {
            return redirect('/event/details/' . $event->eventurl)->with('failure', 'Event entrys are closed');
        }
        

        $eventcompetitions = DB::select("
            SELECT *
            FROM `eventcompetitions`
            WHERE `eventid` = :eventid
            ORDER BY `date` ASC
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

        $schools = null;
        if ($event->schoolrequired) {
            $schools = School::where('visible', 1)->orderby('label')->get();
        }

        $evententry = EventEntry::where('eventid', $event->eventid)
            ->where('userid', $user->userid)
            ->get()
            ->first();

        // Means they need to create an event
        if (empty($evententry)) {
            return view('events.public.registration.createregistration',
                    compact('user', 'event', 'clubs', 'divisionsfinal', 'competitionsfinal',
                        'leaguecompround', 'multipledivisions', 'schools'));
        }

        $entrycompetitions = EntryCompetition::where('entryid', $evententry->entryid)->get();

        $entrycompetitionids = [];
        foreach ($entrycompetitions as $entrycompetition) {
            $entrycompetitionids[$entrycompetition->eventcompetitionid][$entrycompetition->roundid] = $entrycompetition->roundid;
        }

        $divisions = [];
        if ($event->isLeague() || $event->multipledivisions) {
            $divisions = explode(',',$evententry->divisionid);
        }



        // Not empty, means they have entered the event already,
        return view('events.public.registration.updateregistration',
                compact('user', 'event', 'evententry', 'clubs', 'divisionsfinal', 'competitionsfinal',
                    'entrycompetitions', 'entrycompetitionids', 'leaguecompround', 'multipledivisions',
                    'divisions', 'schools'));
    }


    /**
     * POST
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
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
        $evententry->schoolid        = !empty($validated['schoolid'])         ? intval($validated['schoolid'])         : '';
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

    /**
     * POST
     * @param UpdateRegistration $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
        $evententry->schoolid     = !empty($validated['schoolid'])       ? $validated['schoolid']                : '';
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

        $user = User::where('userid', $validated['userid'] ?? -1)->get()->first();

        if (empty($event)) {
            return back()->with('failure', 'Please try again later');
        }


        // could be a manual entry, try lookup by email
        if (empty($user)) {
            $user = User::where('email', $validated['email'] ?? -1)->get()->first();

            // if still empty, create a new user
            if (empty($user)) {
                $user = new User();
                $user->firstname = strtolower($validated['firstname']);
                $user->lastname  = strtolower($validated['lastname']);
                $user->email     = !empty($validated['email']) ? $validated['email'] : $this->createHash(12);
                $user->roleid    = 4;
                $user->username  = strtolower(preg_replace("/[^a-zA-Z0-9]/", "", $validated['firstname'].$validated['lastname'])) . rand(1,1440);
                $user->password  = $this->createHash(12);
                $user->save();
            }
        }

        $validated['userid'] = $user->userid;

        if (empty($validated['email']) && !empty($user->email)) {
            $validated['email'] = $user->email;
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
        $evententry->bib           = !empty($validated['bib'])            ? $validated['bib']                  : '';
        $evententry->address       = !empty($validated['address'])        ? strtolower($validated['address'])    : '';
        $evententry->phone         = !empty($validated['phone'])          ? strtolower($validated['phone'])      : '';
        $evententry->membership    = !empty($validated['membership'])     ? strtolower($validated['membership']) : '';
        $evententry->notes         = !empty($validated['notes'])          ? strtolower($validated['notes'])      : '';
        $evententry->clubid        = !empty($validated['clubid'])         ? intval($validated['clubid'])         : '';
        $evententry->divisionid    = !empty($validated['divisionid'])     ? $validated['divisionid']             : '';
        $evententry->schoolid      = !empty($validated['schoolid'])       ? $validated['schoolid']               : '';
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

        //SendEntryReceived::dispatch($evententry->email, $event->label);

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
        $evententry->bib          = !empty($validated['bib'])            ? $validated['bib']                   : '';
        $evententry->address      = !empty($validated['address'])        ? strtolower($validated['address'])     : '';
        $evententry->phone        = !empty($validated['phone'])          ? strtolower($validated['phone'])       : '';
        $evententry->membership   = !empty($validated['membership'])     ? strtolower($validated['membership'])  : '';
        $evententry->notes        = !empty($validated['notes'])          ? strtolower($validated['notes'])       : '';
        $evententry->clubid       = !empty($validated['clubid'])         ? intval($validated['clubid'])          : '';
        $evententry->divisionid   = !empty($validated['divisionid'])     ? $validated['divisionid']              : '';
        $evententry->schoolid     = !empty($validated['schoolid'])       ? $validated['schoolid']                : '';
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
