<?php

namespace App\Http\Controllers\Events\PublicEvents;


use App\Http\Classes\EventsHelper;
use App\Models\Club;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Event Controller for PUBLIC REQUESTS
 *
 * Class EventController
 * @package App\Http\Controllers\Events\PublicEvents
 */
class EventController extends Controller
{


    public function __construct()
    {
        $this->helper = new EventsHelper();
    }


    /**
     * GET
     * Returns all the events that are open and able to be entered
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getAllEvents()
    {
        $events = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`end` + interval 1 day > now() 
            AND `e`.`visible` = 1
            ORDER BY `e`.`start`
        ");


        return view('events.public.open', compact('events'));
    }


    public function getEventDetails(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $entrycount = DB::table('evententrys')
                        ->where('eventid', $event->eventid)
                        ->count();

        $scorecount = DB::table('scores')
                        ->where('eventid', $event->eventid)
                        ->count();


        $evententryopen = $event->isEvent() ? $event->canEnterEvent() : true;

        // if its a league then check it see its open
        if ($evententryopen && $event->isLeague()) {
            $evententryopen = $event->canEnterLeague();
        }


        $roundlabels = $this->helper->getCompetitionRoundLabels($event);

        $competitiontype = EventType::where('eventtypeid', $event->eventtypeid)->pluck('label')->first();

        $clublabel = Club::where('clubid', $event->clubid)->pluck('label')->first();

        $entries = DB::select("
            SELECT e.firstname, e.lastname, d.label as divisionname
            FROM `evententrys` e
            JOIN `divisions` d USING (`divisionid`)
            WHERE e.`eventid` = :eventid
            ORDER BY `d`.`label`, `e`.`firstname`
        ", ['eventid' => $event->eventid]);

        return view('events.public.details',
            compact('event', 'entries', 'entrycount', 'scorecount', 'evententryopen',
                    'roundlabels', 'competitiontype', 'clublabel'));
    }












    public function getEventScoring(Request $request)
    {
        return view('events.public.scoring');
    }


    public function getPreviousEventsList()
    {
        $events = $this->helper->getPreviousEvents(true, 99);

        return view('events.results.events-list', compact('events'));
    }




}






