<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Events\CreateEvent;
use App\Models\Club;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    public function getAllEvents()
    {
        // get all the events the user can manage
        $events = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
        ", ['userid' => Auth::id()]);

        return view('events.auth.events', compact('events'));
    }

    public function getEventView(Request $request)
    {

        $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            AND `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['userid' => Auth::id(), 'eventurl' => $request->eventurl]);

        $event = !empty($event) ? reset($event) : null;

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Event not found');
        }

        return view('events.auth.manage', compact('event'));
    }

    public function getCreateEventView()
    {
        // if the user is not logged in, or they are just a regular user
        // - redirect to the apply to create page
        if ( !Auth::check() || Auth::user()->roleid > 3) {
            return view('events.public.apply');
        }

        $organisations = Organisation::where('visible', 1)->get();
        $clubs = Club::where('visible', 1)->get();
        // Do some auth checking here. Can the user create an event?
        return view('events.auth.management.create', compact('organisations', 'clubs'));
    }





    /***************************************************************************
     *   POST Requests
     ***************************************************************************/



    public function createEvent(CreateEvent $request)
    {
        $validated = $request->validated();

        $entryclose = !empty($validated['entryclose']) ? new \DateTime($validated['entryclose']) : null;
        $startdate  = new \DateTime($validated['start']);
        $enddate    = new \DateTime($validated['end']);

        $difference = $startdate->diff($enddate)->days;




        $event = new Event();
        $event->label       = ucwords($validated['label']);
        $event->hash        = $this->createHash();
        $event->entryclose  = $entryclose->format('Y-m-d H:i:s');
        $event->start       = $startdate->format('Y-m-d H:i:s');
        $event->end         = $enddate->format('Y-m-d H:i:s');
        $event->daycount    = $difference;
        $event->contactname = !empty($validated['contactname'])     ? $validated['contactname']   : null;
        $event->phone       = !empty($validated['phone'])           ? $validated['phone']         : null;
        $event->email       = !empty($validated['email'])           ? $validated['email']         : null;
        $event->location    = !empty($validated['location'])        ? $validated['location']      : null;
        $event->cost        = !empty($validated['cost'])            ? $validated['cost']          : null;
        $event->bankaccount = !empty($validated['bankaccount'])     ? $validated['bankaccount']   : null;
        $event->bankreference = !empty($validated['bankreference']) ? $validated['bankreference'] : null;
        $event->schedule    = !empty($validated['schedule'])        ? $validated['schedule']      : null;
        $event->info        = !empty($validated['info'])            ? $validated['info']          : null;
        $event->eventstatusid = 6;
        $event->createdby   = Auth::id();
        $event->clubid      = !empty($validated['clubid']) ? $validated['clubid'] : null;
        $event->organisationid = !empty($validated['organisationid']) ? $validated['organisationid'] : null;
        $event->save();

        $event->eventurl    = makeurl($validated['label'], $event->eventid);
        $event->save();

        $eventadmin           = new EventAdmin();
        $eventadmin->userid   = Auth::id();
        $eventadmin->eventid  = $event->eventid;
        $eventadmin->canscore = 1;
        $eventadmin->canedit  = 1;
        $eventadmin->save();

        return redirect('/');

    }
}
