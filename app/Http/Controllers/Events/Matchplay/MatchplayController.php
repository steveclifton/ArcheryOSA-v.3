<?php

namespace App\Http\Controllers\Events\Matchplay;

use App\Http\Classes\EventsHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\MatchplayEvent;
use App\Models\Round;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MatchplayController extends Controller
{

    /** @var Event */
    protected $event;

    public function __construct(Request $request)
    {
        // check to see if they are an event admin and can score
        $this->event  = Event::where('eventurl', $request->eventurl)->first();

        if (empty($this->event)) {
            return redirect('/');
        }
        $eventAdmin = EventAdmin::where('eventid', $this->event->eventid)
            ->where('userid', Auth::id())
            ->where('canscore', 1)
            ->first();

        if (empty($eventAdmin)) {
            return redirect('/');
        }
    }

    public function getCreateMatchplayEventView(Request $request)
    {
        $event = $this->event;

        $matchplayRounds = Round::where('matchplay', 1)->get();

        return view('events.scoring.matchplay.create', compact('event', 'matchplayRounds'));
    }

    public function getMatchplayView(Request $request)
    {
        $event = $this->event;

        return view('events.scoring.matchplay.event-scoring-matchplay', compact('event'));
    }

    public function getMatchplayEventView(Request $request)
    {
        return $this->getMatchplayView($request);
    }

    public function createMatchplayEvent(Request $request)
    {
        if (!$event = $this->event) {
            return back()->with('failure', 'Event Unavailable');
        }

        $ok = $request->eventcompetitionid && $request->divisionid && $request->type && $request->roundid;

        if (!$ok) {
            return back()->with('failure', 'Something went wrong - please try again');
        }

        $eventCompetition = $this->event->getEventCompetition($request->eventcompetitionid);

        if (!$eventCompetition || !$eventCompetition->matchplay) {
            return back()->with('failure', 'Something went wrong - please try again.');
        }

        MatchplayEvent::create([
            'eventid' => $this->event->eventid,
            'eventcompetitionid' => $eventCompetition->eventcompetitionid,
            'gender' => ($request->type[0] ?? 'o'),
            'divisionid' => (int) $request->divisionid,
            'count' => (int) $request->count,
            'roundid' => (int) $request->roundid
        ]);

        return view('events.scoring.matchplay.event-scoring-matchplay', compact('event'))->with('success', 'Matchplay Event Created!');


    }
}
