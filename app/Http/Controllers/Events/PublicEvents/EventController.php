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
            WHERE `e`.`end` > NOW()
            AND `e`.`visible` = 1
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");


        return view('events.public.open', compact('events'));
    }


    public function getEventDetails(Request $request)
    {

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $entrycount     = DB::table('evententrys')
                        ->where('eventid', $event->eventid)
                        ->count();

        $scorecount     = DB::table('scores')
                        ->where('eventid', $event->eventid)
                        ->count();

        $evententryopen = $event->eventstatusid == 1 ? true : false;

        $roundlabels    = $this->helper->getCompetitionRoundLabels($event->eventid);

        $competitiontype = EventType::where('eventtypeid', $event->eventtypeid)->pluck('label')->first();

        $clublabel       = Club::where('clubid', $event->clubid)->pluck('label')->first();

        return view('events.public.details',
            compact('event', 'entrycount', 'scorecount', 'evententryopen', 'roundlabels', 'competitiontype', 'clublabel'));
    }












    public function getEventScoring(Request $request)
    {
        return view('events.public.scoring');
    }


    public function getPreviousEventsList()
    {
        $events = DB::select("
            SELECT e.*, es.label as eventstatus
            FROM `events` e 
            JOIN `eventstatus` es USING (`eventstatusid`)
            JOIN `scores` s USING (`eventid`)
            GROUP BY `s`.`eventid`
            ORDER BY `e`.`promoted` DESC, IFNULL(e.entryclose, e.start) 
        ");

//        dd($events);

        return view('events.results.events-list', compact('events'));
    }




}
