<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateCompetition;
use App\Http\Requests\Admin\UpdateCompetition;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Organisation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CompetitionController extends Controller
{


    /**
     * GET Methods
     */

    /**
     * Returned main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $competitions = Competition::get();
        return view('admin.competitions.competitions', compact('competitions'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {

        // Create the array for mapped rounds
        $rounds = DB::select("
            SELECT r.*, o.label as orgname
            FROM `rounds` r
            LEFT JOIN `organisations` o ON (r.organisationid = o.organisationid)
        ");

        $mappedrounds = [];
        foreach ($rounds as $round) {
            $orgname = !empty($round->orgname) ? $round->orgname : 'Other';

            $roundtype = 'Outdoor';
            if ($round->type == 'i') {
                $roundtype = 'Indoor';
            }
            else if ($round->type == 'f') {
                $roundtype = 'Field';
            }
            else if ($round->type == 'c') {
                $roundtype = 'Clout';
            }
            $mappedrounds[$orgname][$roundtype][] = $round;
        }

        // get all the organisations
        $organisations = Organisation::get();

        return view('admin.competitions.create', compact('mappedrounds', 'organisations'));

    }

    public function getUpdateView(Request $request)
    {
        $competitionid = $request->competitionid ?? null;

        if (empty($competitionid)) {
            return redirect('/admin/competitions')->with('failure', 'Cannot find competition');
        }

        $competition = Competition::where('competitionid', $competitionid)->get()->first();

        // Create the array for mapped rounds
        $rounds = DB::select("
            SELECT r.*, o.label as orgname
            FROM `rounds` r
            LEFT JOIN `organisations` o ON (r.organisationid = o.organisationid)
        ");

        $mappedrounds = [];
        foreach ($rounds as $round) {
            $orgname = !empty($round->orgname) ? $round->orgname : 'Other';

            $roundtype = 'Outdoor';
            if ($round->type == 'i') {
                $roundtype = 'Indoor';
            }
            else if ($round->type == 'f') {
                $roundtype = 'Field';
            }
            else if ($round->type == 'c') {
                $roundtype = 'Clout';
            }
            $mappedrounds[$orgname][$roundtype][] = $round;
        }

        $competitionroundids = CompetitionRound::where('competitionid', $competitionid)->pluck('roundid')->toarray();

        // get all the organisations
        $organisations = Organisation::get();

        return view('admin.competitions.update', compact('competition', 'competitionroundids', 'mappedrounds', 'organisations'));

    }






    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createCompetition(CreateCompetition $request)
    {
        $validated = $request->validated();
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];

        $competition = new Competition();
        $competition->label          = ucwords($validated['label']);
        $competition->organisationid = intval($validated['organisationid']);
        $competition->description    = !empty($validated['description']) ? $validated['description'] : null;
        $competition->visible        = !empty($validated['visible'])     ? 1 : 0;
        $competition->type           = !empty($validated['type'])        ? strtolower($validated['type'])  : 'o';
        $competition->createdby      = Auth::id();
        $competition->save();

        // foreach of the rounds, create a competition-round relationship
        foreach ($roundids as $roundid) {
            if (empty($roundid)) {
                continue;
            }
            $competitionround = new CompetitionRound();
            $competitionround->competitionid = $competition->competitionid;
            $competitionround->roundid       = $roundid;
            $competitionround->save();
        }

        return redirect('/admin/competitions')->with('success', 'Competition created!');
    }


    public function updateCompetition(UpdateCompetition $request)
    {
        $validated = $request->validated();

        // make sure the competition exists
        $competitionid = $request->competitionid ?? null;
        if (empty($competitionid)) {
            return redirect('/admin/competitions')->with('failure', 'Cannot update competition');
        }

        $competition = Competition::where('competitionid', $competitionid)->get()->first();

        // get all the round ids submitted
        $roundids = !empty($validated['roundids']) ? explode(',', $validated['roundids']) : [];


        $competition->label          = ucwords($validated['label']);
        $competition->organisationid = intval($validated['organisationid']);
        $competition->description    = !empty($validated['description'])    ? $validated['description'] : null;
        $competition->visible        = !empty($validated['visible'])        ? 1 : 0;
        $competition->type           = !empty($validated['type'])           ? strtolower($validated['type'])  : 'o';
        $competition->save();

        // get the existing ids
        $existingroundids = CompetitionRound::where('competitionid', $competitionid)->pluck('roundid')->toarray();

        // foreach of the rounds, create a competition-round relationship
        foreach ($roundids as $key => $roundid) {
            if (empty($roundid)) {
                unset($roundids[$key]);
                continue;
            }
            // If this roundid is not in the existing array, means its new, CREATE
            if (!in_array($roundid, $existingroundids)) {
                // create
                $competitionround = new CompetitionRound();
                $competitionround->competitionid = $competition->competitionid;
                $competitionround->roundid       = $roundid;
                $competitionround->save();
            }
        }

        return redirect('/admin/competitions')->with('success', 'Competition Updated!');
    }




}
