<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateRound;
use App\Models\Organisation;
use App\Models\Round;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RoundController extends Controller
{



    /**
     * GET Methods
     */

    /**
     * Return main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $rounds = Round::get();
        return view('admin.rounds.rounds', compact('rounds'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        $organisations = Organisation::get();

        return view('admin.rounds.create', compact('organisations'));

    }




    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createRound(CreateRound $request)
    {
        $validated = $request->validated();

        $club = new Round();
        $club->label          = ucwords($validated['label']);
        $club->organisationid = intval($validated['organisationid']);
        $club->code           = !empty($validated['code'])       ? strtolower($validated['code']) : null;
        $club->dist1          = !empty($validated['dist1'])      ? intval($validated['dist1'])    : null;
        $club->dist1max       = !empty($validated['dist1max'])   ? intval($validated['dist1max']) : null;
        $club->dist2          = !empty($validated['dist2'])      ? intval($validated['dist2'])    : null;
        $club->dist2max       = !empty($validated['dist2max'])   ? intval($validated['dist2max']) : null;
        $club->dist3          = !empty($validated['dist3'])      ? intval($validated['dist3'])    : null;
        $club->dist3max       = !empty($validated['dist3max'])   ? intval($validated['dist3max']) : null;
        $club->dist4          = !empty($validated['dist4'])      ? intval($validated['dist4'])    : null;
        $club->dist4max       = !empty($validated['dist4max'])   ? intval($validated['dist4max']) : null;
        $club->totalmax       = !empty($validated['totalmax'])   ? intval($validated['totalmax']) : null;
        $club->visible        = !empty($validated['visible'])    ? 1 : 0;
        $club->createdby      = Auth::id();
        $club->save();


        return redirect('/admin/rounds');


    }




}
