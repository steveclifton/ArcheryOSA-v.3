<?php

namespace App\Http\Controllers\Events\Auth;


use App\Http\Requests\Auth\Competitions\CreateEventCompetition;
use App\Http\Requests\Auth\Competitions\UpdateEventCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\ScoringLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventCompetitionController extends EventController
{

    public function __construct(Request $request)
    {
        parent::__construct();
        $this->event = Event::where('eventurl', $request->eventurl)->get()->first();

        if (empty($this->event)) {
            return back()->with('failure', 'Invalid');
        }

        $eventadmin = EventAdmin::where('eventid', $this->event->eventid)->get()->first();

        if (empty($eventadmin)) {
            return back()->with('failure', 'Invalid');
        }
    }


    /**
     * Gets the event competition view
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function getEventCompetitionsView(Request $request)
    {
        // Get Event
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


        // Get the events daterange
        $event->daterange = $this->helper->getEventsDateRange($event);

        // Get the first day
        $firstdate = reset($event->daterange)->format('Y-m-d');

        // Add the first competition day to the event
        $competition = EventCompetition::where('eventid', $event->eventid)
            ->where('date', $firstdate)
            ->get()
            ->first();

        if (!empty($competition)) {
            $competition = $competition->toArray();
        }

        // get the competitions mapped into a tree
        //$mappedcompetitions = $this->helper->getMappedCompetitionTree();

        $mappedrounds = $this->helper->getMappedRoundTree();

        $mappeddivisions = $this->helper->getMappedDivisionsTree();

        // get all the scoring levels
        $scoringlevels = ScoringLevel::get();

        // Means the event is a league event
        $leagueweeks = null;
        if ($event->eventtypeid == 2) {
            $leagueweeks = ceil($event->daycount / 7);
        }

        $formaction = empty($competition) ? 'create' : 'update';

        return view('events.auth.management.competitions',
                compact('event',  'mappedrounds', 'competition', 'scoringlevels', 'leagueweeks', 'formaction', 'mappeddivisions')
        );
    }




    /*********************************************************************************************
     *    POST Methods
     ********************************************************************************************/


    /**
     * Creates an event competition
     *
     * @param CreateEventCompetition $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createEventCompetition(CreateEventCompetition $request)
    {
        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'] ?? -1)->get()->first();

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Invalid request');
        }

        // Get array of roundids
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
        $roundidsfinal = [];
        foreach ($roundids as $roundid) {
            if (empty($roundid)) {
                continue;
            }
            $roundidsfinal[] = $roundid;
        }


        // Get array of divisionids
        $divisionids = !empty($validated['divisionids']) ? explode(',', $validated['divisionids']) : [];
        $divisionidsfinal = [];
        foreach ($divisionids as $did) {
            if (empty($did)) {
                continue;
            }
            $divisionidsfinal[] = $did;
        }


        $eventcompetition = new EventCompetition();
        $eventcompetition->eventid          = !empty($validated['eventid'])       ? intval($validated['eventid']) : '';
        $eventcompetition->label            = !empty($validated['label'])         ? ucwords($validated['label']) : '';
        $eventcompetition->date             = !empty($validated['date'])          ? $validated['date'] : '';
        $eventcompetition->location         = !empty($validated['location'])      ? $validated['location'] : '';
        $eventcompetition->schedule         = !empty($validated['schedule'])      ? $validated['schedule'] : '';
        $eventcompetition->roundids         = !empty($roundidsfinal)              ? json_encode($roundidsfinal) : json_encode('');
        $eventcompetition->divisionids      = !empty($divisionidsfinal)           ? json_encode($divisionidsfinal) : json_encode('');
        $eventcompetition->scoringlevel     = !empty($validated['scoringlevel'])  ? intval($validated['scoringlevel']) : 0;
      //$eventcompetition->ignoregenders    = empty($validated['ignoregenders'])  ? 0 : 1;
        $eventcompetition->scoringenabled   = empty($validated['scoringenabled']) ? 0 : 1;
        $eventcompetition->visible          = 1;
        $eventcompetition->save();

        return redirect()->back()->with('success', 'Competition created!');

    }

    /**
     * Updates an eventcompetition
     *
     * @param UpdateEventCompetition $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateEventCompetition(UpdateEventCompetition $request)
    {

        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'] ?? -1)->get()->first();
        $eventcompetition = EventCompetition::where('eventid', $event->eventid ?? -1)
                                            ->where('date', $validated['date'] ?? -1)
                                            ->get()
                                            ->first();

        if (empty($event) || empty($eventcompetition)) {
            return redirect()->back()->with('failure', 'Invalid request');
        }

        // Get array of roundids
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
        $roundidsfinal = [];
        foreach ($roundids as $roundid) {
            if (empty($roundid)) {
                continue;
            }
            $roundidsfinal[] = $roundid;
        }

        // Get array of divisionids
        $divisionids = !empty($validated['divisionids']) ? explode(',', $validated['divisionids']) : [];
        $divisionidsfinal = [];
        foreach ($divisionids as $did) {
            if (empty($did)) {
                continue;
            }
            $divisionidsfinal[] = $did;
        }


        $eventcompetition->eventid          = !empty($validated['eventid'])       ? intval($validated['eventid']) : '';
        $eventcompetition->label            = !empty($validated['label'])         ? ucwords($validated['label']) : '';
        $eventcompetition->date             = !empty($validated['date'])          ? $validated['date'] : '';
        $eventcompetition->location         = !empty($validated['location'])      ? $validated['location'] : '';
        $eventcompetition->schedule         = !empty($validated['schedule'])      ? $validated['schedule'] : '';
        $eventcompetition->roundids         = !empty($roundidsfinal)              ? json_encode($roundidsfinal) : json_encode('');
        $eventcompetition->divisionids      = !empty($divisionidsfinal)           ? json_encode($divisionidsfinal) : json_encode('');
        $eventcompetition->scoringlevel     = !empty($validated['scoringlevel'])  ? intval($validated['scoringlevel']) : 0;
        // $eventcompetition->ignoregenders = empty($validated['ignoregenders']) ? 0 : 1;
        $eventcompetition->scoringenabled   = empty($validated['scoringenabled']) ? 0 : 1;
//        $eventcompetition->visible          = empty($validated['visible'])        ? 0 : 1;
        $eventcompetition->save();

        return redirect()->back()->with('success', 'Competition updated!');

    }
}
