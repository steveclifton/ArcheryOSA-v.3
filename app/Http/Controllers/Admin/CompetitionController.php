<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateCompetition;
use App\Models\Competition;
use App\Models\CompetitionRound;
use App\Models\Organisation;
use App\Http\Controllers\Controller;
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
        $competition->description    = !empty($validated['description']) ? $validated['visible'] : null;
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


}
