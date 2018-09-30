<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\ScoringLevel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class Ajax extends EventController
{
    public function getUser(Request $request)
    {
        if (empty($request->search)) {
            return response()->json([
                'success' => false,
                'data'    => []
            ]);
        }


        if (is_numeric($request->search)) {
            $user = User::where('userid', $request->search)->take(3)->get();
        }
        else {

            $user = User::where('email', 'like', $request->search . '%')->take(3)->get();
        }

        if (!empty($user) && count($user) < 3 && !is_numeric($request->search)) {
            $user[] = $user;
            $user = User::where('firstname', 'like', $request->search . '%')->take(3)->get();
        }


        return response()->json([
            'success' => true,
            'data'    => $user
        ]);
    }

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

        $entries = EntryCompetition::where('eventid', $event->eventid)
            ->where('eventcompetitionid',  $competition['eventcompetitionid'])
            ->get()->first();

        /**
         * Get the text box view data
         */
        // Make the view
        $view = View::make('events.auth.management.includes.competitiontext', compact('competition'));
        $html = $view->render();

        /*
         * Get the compeititon tree info
         */
        //$mappedcompetitions = $this->helper->getMappedCompetitionTree();
        $mappeddivisions    = $this->helper->getMappedDivisionsTree();
        $mappedrounds       = $this->helper->getMappedRoundTree();

        // Make the view
        $view = View::make('events.auth.management.includes.competitiontree', compact('competition', 'entries', 'mappedrounds', 'mappeddivisions'));
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
