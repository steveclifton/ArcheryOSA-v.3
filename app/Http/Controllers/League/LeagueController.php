<?php

namespace App\Http\Controllers\League;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LeagueController extends Controller
{



    public function getUserLeagueScoringView(Request $request)
    {
        $event = Event::where('eventurl', $request->eventurl ?? -1)->get()->first();

        if (empty($event)) {
            return redirect('/');
        }



        dd($request);
    }

}
