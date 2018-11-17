<?php

namespace App\Http\Controllers\Events\Auth;


use App\Http\Requests\Auth\Competitions\CreateEventCompetition;
use App\Http\Requests\Auth\Competitions\CreateLeagueCompetition;
use App\Http\Requests\Auth\Competitions\UpdateEventCompetition;
use App\Http\Requests\Auth\Competitions\UpdateLeagueCompetition;
use App\Models\Competition;
use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\EventEntry;
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
        if (Auth::user()->isSuperAdmin()) {
            $event = DB::select("
            SELECT e.*, es.label as status
            FROM `events` e
            JOIN `eventstatus` es USING (`eventstatusid`)
            WHERE `e`.`eventurl` = :eventurl
            LIMIT 1
        ",['eventurl' => $request->eventurl]);
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

        // Get mapped rounds
        $mappedrounds = $this->helper->getMappedRoundTree();

        // Get mapped divisions
        $mappeddivisions = $this->helper->getMappedDivisionsTree();

        // get all the scoring levels
        $scoringlevels = ScoringLevel::get();

        // Means the event is a league event
        if ($event->eventtypeid == 2) {

            // Add the first competition day to the event
            $competition = EventCompetition::where('eventid', $event->eventid)
                ->get()
                ->first();

            $entries = EntryCompetition::where('eventid', $event->eventid)->get()->first();

            $formaction = empty($competition) ? 'create' : 'update';

            return view('events.auth.management.league.competition',
                compact('event', 'entries', 'mappedrounds', 'competition', 'scoringlevels', 'formaction', 'mappeddivisions')
            );

        }

        // Means the event is a postal event
        if ($event->eventtypeid == 3) {
            // Get the events daterange
            $event->daterange = $this->helper->getPostalEventDateRange($event);

            // Get the first day
            $firstdate = reset($event->daterange);

            // Add the first competition day to the event
            $competition = EventCompetition::where('eventid', $event->eventid)
                ->where('date', $firstdate)
                ->get()
                ->first();

            if (!empty($competition)) {
                $competition = $competition->toArray();
            }

            $formaction = empty($competition) ? 'create' : 'update';

            $entries = EntryCompetition::where('eventid', $event->eventid)
                ->where('eventcompetitionid',  $competition['eventcompetitionid'])
                ->get()->first();

            $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

            return view('events.auth.management.postal.competitions',
                compact('event', 'entries', 'mappedrounds', 'competition', 'eventcompetitions',
                    'scoringlevels', 'formaction', 'mappeddivisions')
            );
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

        $formaction = empty($competition) ? 'create' : 'update';

        $entries = EntryCompetition::where('eventid', $event->eventid)
            ->where('eventcompetitionid',  $competition['eventcompetitionid'])
            ->get()->first();

        $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();


        return view('events.auth.management.competitions',
                compact('event', 'entries', 'mappedrounds', 'competition', 'eventcompetitions',
                    'scoringlevels', 'formaction', 'mappeddivisions')
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





    // League
    public function createLeagueCompetition(CreateLeagueCompetition $request)
    {
        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'] ?? -1)->get()->first();

        if (empty($event)) {
            return redirect()->back()->with('failure', 'Invalid request');
        }

        // Get array of roundids
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
        $roundids = array_filter($roundids);

        if (empty($roundids) || count($roundids) > 1) {
            return back()->withinput()->with('failure', 'Please choose only 1 round for the league');
        }

        $roundid = reset($roundids);


        // Get array of divisionids
        $divisionids = !empty($validated['divisionids']) ? explode(',', $validated['divisionids']) : [];
        $divisionidsfinal = [];
        foreach ($divisionids as $did) {
            if (empty($did)) {
                continue;
            }
            $divisionidsfinal[] = $did;
        }

        $currentWeek = $event->isLeague() ? 1 : 0;

        $eventcompetition = new EventCompetition();
        $eventcompetition->eventid          = !empty($validated['eventid'])       ? intval($validated['eventid']) : '';
        $eventcompetition->label            = !empty($validated['label'])         ? ucwords($validated['label']) : '';
        $eventcompetition->location         = !empty($validated['location'])      ? $validated['location'] : '';
        $eventcompetition->schedule         = !empty($validated['schedule'])      ? $validated['schedule'] : '';
        $eventcompetition->roundids         = intval($roundid);
        $eventcompetition->currentweek      = $currentWeek;
        $eventcompetition->divisionids      = !empty($divisionidsfinal)           ? json_encode($divisionidsfinal) : json_encode('');
        $eventcompetition->scoringlevel     = !empty($validated['scoringlevel'])  ? intval($validated['scoringlevel']) : 0;
        $eventcompetition->ignoregenders    = empty($validated['ignoregenders'])  ? 0 : 1;
        $eventcompetition->multipledivisions    = empty($validated['multipledivisions'])  ? 0 : 1;
        $eventcompetition->scoringenabled   = empty($validated['scoringenabled']) ? 0 : 1;
        $eventcompetition->visible          = 1;
        $eventcompetition->save();

        return redirect()->back()->with('success', 'Competition created!');

    }

    public function updateLeagueCompetition(UpdateLeagueCompetition $request)
    {

        $validated = $request->validated();

        $event = Event::where('eventid', $validated['eventid'] ?? -1)->get()->first();

        $eventcompetition = EventCompetition::where('eventid', $event->eventid ?? -1)
                                            ->get()
                                            ->first();

        if (empty($event) || empty($eventcompetition)) {
            return redirect()->back()->with('failure', 'Invalid request');
        }


        // Get array of roundids
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];
        $roundids = array_filter($roundids);

        if (empty($roundids) || count($roundids) > 1) {
            return back()->withinput()->with('failure', 'Please choose only 1 round for the league');
        }
        // get first one
        $roundid = reset($roundids);


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
        $eventcompetition->location         = !empty($validated['location'])      ? $validated['location'] : '';
        $eventcompetition->schedule         = !empty($validated['schedule'])      ? $validated['schedule'] : '';
        $eventcompetition->roundids         = intval($roundid);
        $eventcompetition->divisionids      = !empty($divisionidsfinal)           ? json_encode($divisionidsfinal) : json_encode('');
        $eventcompetition->scoringlevel     = !empty($validated['scoringlevel'])  ? intval($validated['scoringlevel']) : 0;
        $eventcompetition->ignoregenders    = empty($validated['ignoregenders']) ? 0 : 1;
        $eventcompetition->multipledivisions = empty($validated['multipledivisions'])  ? 0 : 1;

        $eventcompetition->scoringenabled   = empty($validated['scoringenabled']) ? 0 : 1;
//        $eventcompetition->visible          = empty($validated['visible'])        ? 0 : 1;
        $eventcompetition->save();

        return redirect()->back()->with('success', 'Competition updated!');

    }





}
