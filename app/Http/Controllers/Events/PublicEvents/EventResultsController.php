<?php

namespace App\Http\Controllers\Events\PublicEvents;

use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EventResultsController extends EventController
{
    /**
     * Get the Events competitions and their results status
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory| \Illuminate\Http\RedirectResponse| \Illuminate\Routing\Redirector| \Illuminate\View\View
     */
    public function getEventResultsList(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->orderBy('date', 'asc')->get();

        $overall = $event->showoverall;
        foreach ($eventcompetitions as $eventcompetition) {

            $eventcompetition->score = Score::where('eventid', $eventcompetition->eventid)
                                            ->where('eventcompetitionid', $eventcompetition->eventcompetitionid)
                                            ->get()
                                            ->first();

        }
        return view('events.results.eventcompetitions', compact('event', 'eventcompetitions', 'overall'));
    }


    /**
     *
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|void
     */
    public function getEventCompetitionResults(Request $request)
    {
        if (empty($request->eventcompetitionid) || empty($request->eventurl)) {
            return back()->with('failure', 'Invalid Request');
        }

        $event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (strcasecmp($request->eventcompetitionid, 'overall') === 0) {
            return $this->getEventOverallResults($event);
        }

        // Get the results for the event and the eventcompetitionid
        return $this->getEventCompResults($event, $request->eventcompetitionid);


    }


    /**
     * @param Event $event
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    private function getEventOverallResults(Event $event)
    {
        return back()->with('failure', 'Coming soon');

        // Event Entries
        $entrys = DB::select("
            SELECT ee.*, ec.entrycompetitionid, ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label      
        ");



        $evententrys = [];
        foreach ($entrys as $entry) {
            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            $evententrys[$entry->bowtype][$gender . $entry->divisionname][$entry->userid][] = $entry;
        }


        return view('events.results.results', compact('event', 'evententrys', 'eventcompetition'));

    }



    private function getEventCompResults(Event $event, $eventcompetitionid)
    {
        $entrys = DB::select("
            SELECT ee.firstname, ee.lastname, ee.gender, ec.entrycompetitionid, 
                ec.eventcompetitionid, ec.roundid, d.label as divisionname, d.bowtype, r.unit,
                sf.*
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec USING (`entryid`)
            JOIN `divisions` d ON (`ec`.`divisionid` = `d`.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `scores_flat` sf ON (ee.entryid = sf.entryid AND ec.entrycompetitionid = sf.entrycompetitionid AND ec.roundid = sf.roundid)
            WHERE `ee`.`eventid` = '".$event->eventid."'
            AND `ec`.`eventcompetitionid` = :eventcompetitionid
            AND `ee`.`entrystatusid` = 2
            ORDER BY `d`.label      
        ", ['eventcompetitionid' => $eventcompetitionid]);


        $evententrys = [];
        foreach ($entrys as $entry) {
            $gender = $entry->gender == 'm' ? 'Men\'s ' : 'Women\'s ';
            $evententrys[$entry->bowtype][$gender . $entry->divisionname][] = $entry;
        }

        $eventcompetition = EventCompetition::where('eventcompetitionid', $eventcompetitionid)->get()->first();

        return view('events.results.results', compact('event', 'evententrys', 'eventcompetition'));

    }

}
