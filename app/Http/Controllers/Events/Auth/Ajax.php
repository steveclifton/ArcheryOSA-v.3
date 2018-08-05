<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\ScoringLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Ajax extends EventController
{

    public function getMarkup(Request $request)
    {

        $event = Event::where('eventid', $request->eventid ?? -1)->get()->first();

        if (empty($event) || empty($request->date)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Request, please try again later'
            ]);
        }

        /*
         * Get the competition
         */
        $competition = EventCompetition::where('eventid', $request->eventid)
            ->where('date', $request->date)
            ->get()
            ->first();

        if (!empty($competition)) {
            $competition = $competition->toArray();
        }

        /**
         * Get the text box view data
         */
        // Make the view
        $view = View::make('events.auth.management.includes.competitiontext', compact('competition'));
        $html = $view->render();

        /*
         * Get the compeititon tree info
         */
        $mappedcompetitions = $this->helper->getMappedCompetitionTree();
        $mappeddivisions = $this->helper->getMappedDivisionsTree();

        // Make the view
        $view = View::make('events.auth.management.includes.competitiontree', compact('competition','mappedcompetitions', 'mappeddivisions'));
        $html .= $view->render();


        /**
         * Get the competitionoptions view data
         */
        // get all the scoring levels
        $scoringlevels = ScoringLevel::get();

        // Means the event is a league event
        $leagueweeks = null;
        if ($event->eventtypeid == 2) {
            $leagueweeks = ceil($event->daycount / 7);
        }

        // Add data to the return view
        $view = View::make('events.auth.management.includes.competitionoptions', compact('competition', 'scoringlevels', 'leagueweeks'));
        $html .= $view->render();

        // change the form action based on whether it exists or not
        $formaction = '/events/manage/competitions/update/'.$event->eventurl;
        if (empty($competition)) {
            $formaction = '/events/manage/competitions/create/'.$event->eventurl;
        }

        return response()->json([
            'success'    => true,
            'html'       => $html,
            'formaction' => $formaction
        ]);
    }
}
