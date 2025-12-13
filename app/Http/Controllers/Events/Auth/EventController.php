<?php

namespace App\Http\Controllers\Events\Auth;

use App\Http\Classes\EventsHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\League\LeagueController;
use App\Http\Requests\Auth\Events\CreateEvent;
use App\Http\Requests\Auth\Events\UpdateEvent;
use App\Model\Audit;
use App\Models\Club;

use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\EventEntry;
use App\Models\EventType;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Classes\Competitions\Template;

class EventController extends Controller
{
    protected EventsHelper $helper;

    public function __construct()
    {
        $this->helper = new EventsHelper();
    }

    /*****************************************
     * Primary Create and Update views
     *****************************************/

    public function getCreateEventView()
    {
        // if the user is not logged in, or they are just a regular user
        // - redirect to the apply to create page
        if (!Auth::check() || Auth::user()->roleid > 3) {
            return view('events.public.apply');
        }

        $organisations = Organisation::where('visible', 1)->get();
        $clubs = Club::where('visible', 1)->orderby('label')->get();
        $eventtypes = EventType::get();
        $eventlevels = $this->helper->getEventLevels();
        $regions = $this->helper->getNZRegions();

        // Do some auth checking here. Can the user create an event?
        return view('events.auth.management.create', compact('organisations', 'clubs', 'eventtypes', 'eventlevels', 'regions'));
    }

    public function getUpdateEventView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        if (empty($event) || empty(Auth::user()->canEditEvent($event->eventid))) {
            return redirect()->back()->with('failure', 'Invalid request');
        }

        $organisations = Organisation::where('visible', 1)->get();
        $clubs         = Club::where('visible', 1)->get();
        $eventtypes    = EventType::get();
        $eventlevels   = $this->helper->getEventLevels();
        $regions = $this->helper->getNZRegions();

        return view('events.auth.management.update', compact('event', 'organisations', 'clubs', 'eventtypes', 'eventlevels', 'regions'));
    }


    /*****************************************
     *
     *****************************************/

    public function getAllEvents()
    {
        // check to see they are auth level first
        if (Auth::user()->roleid == 4) {
            return back()->with('failure', 'Unavailable to access');
        }

        if (Auth::user()->isSuperAdmin()) {
            $events = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            GROUP BY `e`.`eventid`
            ORDER BY `e`.`start` DESC
        ");
        }
        else {
            $events = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventadmins` ea USING (`eventid`)
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `ea`.`userid` = :userid
            GROUP BY `e`.`eventid`
            ORDER BY `e`.`start` DESC
        ", ['userid' => Auth::id()]);
        }

        return view('events.auth.events', compact('events'));
    }

    public function getEventManageView(Request $request)
    {
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ", ['eventurl' => $request->eventurl]);
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

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

        return view('events.auth.manage', compact('event', 'eventcompetitions'));
    }


    // Scoring
    public function getUserEventScoringList()
    {
        // get all the events the user can manage
        // - just a league at this stage
        $events = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e
            JOIN `evententrys` ee ON (e.eventid = ee.eventid)
            JOIN `eventcompetitions` ec on (e.`eventid` = ec.eventid)
            JOIN `eventstatus` es ON (e.`eventstatusid` = es.eventstatusid)
            WHERE (`ee`.`userid` = :userid 
                        OR `ee`.`userid` IN (
                    SELECT `userid`
                    FROM `users`
                    WHERE `parentuserid` = :parentuserid
                ) )
            AND `ee`.`entrystatusid` = 2
            AND `e`.`eventstatusid` = 1
            AND `ec`.`scoringlevel` = 2
            AND `ec`.`scoringenabled` = 1
            AND (`e`.`eventtypeid` = 2 OR `e`.`eventtypeid` = 3)
            GROUP BY `e`.`eventid`
            ORDER BY `e`.`start`
        ", ['userid' => Auth::id(), 'parentuserid' => Auth::id()]);

        return view('events.auth.scoringlist', compact('events'));
    }


    public function getUserEventScoringView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $leagueController = new LeagueController();
        return $leagueController->getUserLeagueScoringView($event);
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

        $difference = $startdate->diff($enddate)->days + 1;


        $event = new Event();
        $event->label           = ucwords($validated['label']);
        $event->hash            = $this->createHash();
        $event->entryclose      = !empty($entryclose) ? $entryclose->format('Y-m-d H:i:s') : null;
        $event->start           = $startdate->format('Y-m-d H:i:s');
        $event->end             = $enddate->format('Y-m-d H:i:s');
        $event->daycount        = $difference;
        $event->contactname     = !empty($validated['contactname'])     ? $validated['contactname']   : null;
        $event->phone           = !empty($validated['phone'])           ? $validated['phone']         : null;
        $event->region          = !empty($validated['region'])           ? $validated['region']         : null;
        $event->level           = !empty($validated['level'])           ? $validated['level']         : null;
        $event->email           = !empty($validated['email'])           ? $validated['email']         : null;
        $event->location        = !empty($validated['location'])        ? $validated['location']      : null;
        $event->cost            = !empty($validated['cost'])            ? $validated['cost']          : null;
        $event->bankaccount     = !empty($validated['bankaccount'])     ? $validated['bankaccount']   : null;
        $event->bankreference   = !empty($validated['bankreference']) ? $validated['bankreference'] : null;
        $event->schedule        = !empty($validated['schedule'])        ? $validated['schedule']      : null;
        $event->info            = !empty($validated['info'])            ? $validated['info']          : null;
        $event->eventstatusid   = 1;
        $event->status          = 'Open';
        $event->createdby       = Auth::id();
        $event->clubid          = !empty($validated['clubid']) ? $validated['clubid'] : null;
        $event->organisationid  = !empty($validated['organisationid']) ? $validated['organisationid'] : null;
        $event->visible         = 1;
        $event->showoverall     = 1;
        $event->adminnotifications = 1;
        $event->imagedt         = 'event' . rand(1,2) . '.jpg';
        $event->eventtypeid     = intval($validated['eventtypeid']);

        $event->save();

        $event->eventurl    = makeurl($validated['label'], $event->eventid);
        $event->save();

        $eventadmin           = new EventAdmin();
        $eventadmin->userid   = Auth::id();
        $eventadmin->eventid  = $event->eventid;
        $eventadmin->canscore = 1;
        $eventadmin->canedit  = 1;
        $eventadmin->save();

        Audit::create([
            'eventid' => $event->eventid,
            'userid' => Auth::id(),
            'class' => __CLASS__,
            'method' => __FUNCTION__,
            'line' => __LINE__,
            'before' => json_encode(['event' => $event, 'eventadmin' => $eventadmin])
        ]);

        // If they have requested a template, create the requrired data
        if (!empty($request->template)) {

            $template = new Template($event);
            $template->create($request->template);

        }

        Cache::forget('upcomingevents');

        return redirect('/events/manage/' . $event->eventurl);

    }



    public function updateEvent(UpdateEvent $request)
    {
        $validated = $request->validated();

        $event = Event::where('eventurl', $request->eventurl ?? null)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $eventBefore = clone $event;

        $entryclose = !empty($validated['entryclose']) ? new \DateTime($validated['entryclose']) : null;
        $startdate  = new \DateTime($validated['start']);
        $enddate    = new \DateTime($validated['end']);

        $difference = $startdate->diff($enddate)->days + 1;

        $dateranges = [];
        foreach ($this->helper->getDateRange($validated['start'], $validated['end']) as $date) {
            $dateranges[] = $date->format('Y-m-d');
        }

        $olddateranges = [];
        foreach($this->helper->getDateRange($event->start, $event->end) as $date) {
            $olddateranges[] = $date->format('Y-m-d');
        }

        if ((count($dateranges) != count($olddateranges)) && !empty($dateranges)) {

            // get the event competitions that are not in the new date range
            $eventcompetitions = EventCompetition::where('eventid', $event->eventid)
                ->whereNotIn('date', ($dateranges))
                ->get();

            // delete the entry competition where the dates havechanged
            // delete eventcompetitions where the dates have changed
            foreach ($eventcompetitions as $eventcompetition) {
                EntryCompetition::where('eventid', $event->eventid)
                    ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                    ->delete();

                $eventcompetition->delete();
            }
        }

        $event->label          = ucwords($validated['label']);
        $event->hash           = $this->createHash();
        $event->entryclose     = !empty($entryclose) ? $entryclose->format('Y-m-d H:i:s') : null;
        $event->start          = $startdate->format('Y-m-d H:i:s');
        $event->end            = $enddate->format('Y-m-d H:i:s');
        $event->daycount       = $difference;
        $event->contactname    = !empty($validated['contactname'])     ? $validated['contactname']   : null;
        $event->region          = !empty($validated['region'])           ? $validated['region']         : null;
        $event->phone          = !empty($validated['phone'])           ? $validated['phone']         : null;
        $event->level          = !empty($validated['level'])           ? $validated['level']         : null;
        $event->email          = !empty($validated['email'])           ? $validated['email']         : null;
        $event->location       = !empty($validated['location'])        ? $validated['location']      : null;
        $event->cost           = !empty($validated['cost'])            ? $validated['cost']          : null;
        $event->bankaccount    = !empty($validated['bankaccount'])     ? $validated['bankaccount']   : null;
        $event->bankreference  = !empty($validated['bankreference'])   ? $validated['bankreference'] : null;
        $event->schedule       = !empty($validated['schedule'])        ? $validated['schedule']      : null;
        $event->info           = !empty($validated['info'])            ? $validated['info']          : null;
        $event->clubid         = !empty($validated['clubid'])          ? $validated['clubid']        : null;
        $event->organisationid = !empty($validated['organisationid']) ? $validated['organisationid'] : null;

        $event->save();

        $event->eventurl       = makeurl($validated['label'], $event->eventid);
        $event->save();

        Audit::create([
            'eventid' => $event->eventid,
            'userid' => Auth::id(),
            'class' => __CLASS__,
            'method' => __FUNCTION__,
            'line' => __LINE__,
            'before' => json_encode(['event' => $eventBefore]),
            'after' => json_encode(['event' => $event])
        ]);

        Cache::forget('upcomingevents');
        return redirect('events/manage/update/' . $event->eventurl)->with('success', 'Event updated!');

    }


}
