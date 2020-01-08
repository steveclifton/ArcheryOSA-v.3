<?php

namespace App\Http\Controllers\Events\Auth;

use App\Models\EntryCompetition;
use App\Models\Event;
use App\Models\EventCompetition;
use App\Models\ScoringLevel;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class AjaxController extends EventController
{
    public function getUser(Request $request)
    {
        if (empty($request->search)) {
            return response()->json([
                'success' => false,
                'data'    => []
            ]);
        }

        $users = DB::select("
            SELECT *
            FROM `users` u
            WHERE CONCAT_WS(' ', u.`firstname`, u.`lastname`) LIKE :search
            LIMIT 3
        ", ['search'=> '%' . $request->search . '%']);

        foreach ($users as $user) {
            $user->userid = htmlentities($user->userid);
            $user->email = htmlentities($user->email);
            $user->firstname = htmlentities($user->firstname);
            $user->lastname = htmlentities($user->lastname);
            $user->clubid = htmlentities($user->clubid);
            $user->gender = htmlentities($user->gender);
            $user->membership = htmlentities($user->membership);
            $user->phone = htmlentities($user->phone);
        }

        return response()->json([
            'success' => true,
            'data'    => $users
        ]);
    }

    public function getMarkup(Request $request)
    {
        $event = Event::where('eventid', $request->eventid ?? -1)->first();

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
                                        ->where('eventcompetitionid', $request->eventcompetitionid)
                                        ->first();

        if (!empty($competition)) {
            $competition = $competition->toArray();
        }

        if ($event->isPostal()) {
            // Get the events daterange
            $event->daterange = $this->helper->getPostalEventDateRange($event);

            $view = View::make('events.auth.management.includes.postal.dateselect', compact('event', 'competition'));
            $html = $view->render();
        }
        else {
            // regular event

            // Get the events daterange
            $event->daterange = $this->helper->getEventsDateRange($event);

            $eventcompetitions = EventCompetition::where('eventid', $event->eventid)->get();

            $view = View::make('events.auth.management.includes.dateselect', compact('event', 'competition', 'eventcompetitions'));
            $html = $view->render();
        }


        /**
         * Get the text box view data
         */
        // Make the view
        $view = View::make('events.auth.management.includes.competitiontext', compact('competition'));
        $html .= $view->render();

        /*
         * Get the compeititon tree info
         */
        $mappeddivisions    = $this->helper->getMappedDivisionsTree();
        $mappedrounds       = $this->helper->getMappedRoundTree();

        // Make the view
        $view = View::make('events.auth.management.includes.competitiontree', compact('competition', 'mappedrounds', 'mappeddivisions'));
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
