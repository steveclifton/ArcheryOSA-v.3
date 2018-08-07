<?php

namespace App\Http\Controllers\Events\PublicEvents;


use App\Http\Classes\EventsHelper;
use App\Models\Event;
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
        return view('events.public.details', compact('event'));
    }






    public function getEventResults(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }
        return view('events.public.results', compact('event'));
    }


    public function getEventScoring(Request $request)
    {
        return view('events.public.scoring');
    }


    public function getPreviousEvents()
    {
        return view('events.completed.events');
    }




}
