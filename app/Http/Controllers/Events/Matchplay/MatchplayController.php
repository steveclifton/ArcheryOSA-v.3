<?php

namespace App\Http\Controllers\Events\Matchplay;

use App\Http\Classes\EventsHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventAdmin;
use App\Models\EventCompetition;
use App\Models\MatchplayEvent;
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

        return view('events.scoring.matchplay.create', compact('event'));
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
}
