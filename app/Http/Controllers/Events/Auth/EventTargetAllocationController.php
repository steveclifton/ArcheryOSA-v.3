<?php

namespace App\Http\Controllers\Events\Auth;


use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\EventEntry;
use App\Models\ScoringLevel;
use App\Models\TargetAllocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class EventTargetAllocationController extends EventController
{

    protected function getUsers($eventid, $eventcompetitionid = null)
    {
        $data = ['eventid' => $eventid];
        $and = '';

        if (!empty($eventcompetitionid)) {
            $data['eventcompid'] = $eventcompetitionid;
            $and = " AND ec.`eventcompetitionid` = :eventcompid";

        }

        $and .= " ORDER BY `ta`.`target`+0";

        return DB::select("
            SELECT CONCAT(ee.firstname, ' ', ee.lastname) as fullname, ec.entrycompetitionid, 
                   d.label as divisionname, r.label as roundname, ee.entryid, ta.target, ta.info
            FROM `evententrys` ee
            JOIN `entrycompetitions` ec ON (ee.`entryid` = ec.`entryid`)
            JOIN `divisions` d ON (ec.`divisionid` = d.`divisionid`)
            JOIN `rounds` r ON (ec.roundid = r.roundid)
            LEFT JOIN `targetallocations` ta ON (`ta`.`entrycompetitionid` = ec.`entrycompetitionid`)
            WHERE ee.`eventid` = :eventid
            
        " . $and, $data);
    }


    public function getTargetAllocationsList(Request $request)
    {
        $event = $this->userOk($request->eventurl);

        if (empty($event)) {
            return back()->with('failure', 'Cannot access Target Allocations');
        }

        // get the events competitions
        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();
        $firsteventcomp = $eventcompetitions->first();

        // get a list of all the event entries
        $entries = $this->getUsers($event->eventid, $firsteventcomp->eventcompetitionid ?? null);

        return view('events.auth.management.targetallocations', compact('event', 'eventcompetitions', 'entries'));
    }

    public function getTargetAllocationsTable(Request $request)
    {
        $event = $this->userOk($request->eventurl);
        $eventcompetition = EventCompetition::where('eventcompetitionid', $request->eventcompetitionid)->get()->first();

        if (empty($event) || empty($eventcompetition)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $entries = $this->getUsers($event->eventid, $eventcompetition->eventcompetitionid);

        /**
         * Get the text box view data
         */
        // Make the view
        $view = View::make('events.auth.management.includes.targettable', compact('entries'));
        $html = $view->render();


        return response()->json([
            'success'    => true,
            'html'       => $html,
        ]);

    }

    public function UpdateTargetAllocation(Request $request)
    {
        $event            = $this->userOk($request->eventurl);
        $eventcompetition = EntryCompetition::where('entrycompetitionid', $request->entrycompid)->get()->first();
        $note             = $request->note;
        $target           = $request->target;

        if (empty($event) || empty($eventcompetition)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        $targetallocation = TargetAllocation::where('entrycompetitionid', $request->entrycompid)->get()->first();

        $targetallocation = $targetallocation ?? new TargetAllocation();

        $targetallocation->userid = $eventcompetition->userid;
        $targetallocation->eventid = $event->eventid;
        $targetallocation->eventcompetitionid = $eventcompetition->eventcompetitionid;
        $targetallocation->entrycompetitionid = $eventcompetition->entrycompetitionid;
        $targetallocation->divisionid = $eventcompetition->divisionid;
        $targetallocation->roundid = $eventcompetition->roundid;
        $targetallocation->target = $target;
        $targetallocation->info = $note;

        $targetallocation->save();

        return response()->json([
            'success'    => true,
        ]);

    }





}
