<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateRound;
use App\Http\Requests\Admin\UpdateRound;
use App\Models\AnzRecordRounds;
use App\Models\Organisation;
use App\Models\Round;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $rounds = DB::select("
            SELECT r.*, o.label as orgname
            FROM `rounds` r 
            LEFT JOIN `organisations` o USING (`organisationid`)
            ORDER BY o.`label`, r.`label` 
        ");
        return view('admin.rounds.rounds', compact('rounds'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        $organisations = Organisation::get();

        $anzRecordRounds = AnzRecordRounds::all();

        return view('admin.rounds.create', compact('organisations', 'anzRecordRounds'));

    }

    public function getUpdateView(Request $request)
    {
        $roundid = $request->roundid ?? null;
        if (empty($roundid)) {
            return redirect('/admin/rounds');
        }

        $round = Round::where('roundid', $roundid)->get()->first();
        $organisations = Organisation::get();

        $anzRecordRounds = AnzRecordRounds::all();

        return view('admin.rounds.update', compact('round', 'organisations', 'anzRecordRounds'));
    }


    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createRound(CreateRound $request)
    {
        $validated = $request->validated();

        $round = new Round();
        $round->label          = ucwords($validated['label']);
        $round->organisationid = intval($validated['organisationid']);
        $round->code           = !empty($validated['code'])       ? strtolower($validated['code']) : null;
        $round->dist1          = !empty($validated['dist1'])      ? intval($validated['dist1'])    : null;
        $round->dist1max       = !empty($validated['dist1max'])   ? intval($validated['dist1max']) : null;
        $round->dist2          = !empty($validated['dist2'])      ? intval($validated['dist2'])    : null;
        $round->dist2max       = !empty($validated['dist2max'])   ? intval($validated['dist2max']) : null;
        $round->dist3          = !empty($validated['dist3'])      ? intval($validated['dist3'])    : null;
        $round->dist3max       = !empty($validated['dist3max'])   ? intval($validated['dist3max']) : null;
        $round->dist4          = !empty($validated['dist4'])      ? intval($validated['dist4'])    : null;
        $round->dist4max       = !empty($validated['dist4max'])   ? intval($validated['dist4max']) : null;
        $round->totalmax       = !empty($validated['totalmax'])   ? intval($validated['totalmax']) : null;
        $round->visible        = !empty($validated['visible'])    ? 1 : 0;
        $round->matchplay        = !empty($validated['matchplay'])    ? 1 : 0;
        $round->type           = !empty($validated['type'])       ? strtolower($validated['type'])  : 'o';

        $errors = [];
        if (!empty($validated['anz_record_id'])) {
            if ( ! $this->hasExistingRecordMapping(0, $validated['anz_record_id'])) {
                $round->anz_record_id = $validated['anz_record_id'];
            }
            else {
                $errors[] = 'Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist1_id'])) {
            if ( ! $this->hasExistingRecordMapping(0, $validated['anz_record_dist1_id'])) {
                $round->anz_record_dist1_id = $validated['anz_record_dist1_id'];
            }
            else {
                $errors[] = 'Distance 1 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist2_id'])) {
            if ( ! $this->hasExistingRecordMapping(0, $validated['anz_record_dist2_id'])) {
                $round->anz_record_dist2_id = $validated['anz_record_dist2_id'];
            }
            else {
                $errors[] = 'Distance 2 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist3_id'])) {
            if ( ! $this->hasExistingRecordMapping(0, $validated['anz_record_dist3_id'])) {
                $round->anz_record_dist3_id = $validated['anz_record_dist3_id'];
            }
            else {
                $errors[] = 'Distance 3 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist4_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_dist4_id'])) {
                $round->anz_record_dist4_id = $validated['anz_record_dist4_id'];
            }
            else {
                $errors[] = 'Distance 4 Anz Record has existing mapping';
            }
        }

        if ($errors) {
            return back()->with('failure', $errors);
        }

        $round->createdby      = Auth::id();
        $round->save();


        return redirect('/admin/rounds')->with('success', 'Round Created!');
    }

    public function updateRound(UpdateRound $request)
    {
        $validated = $request->validated();

        $round = Round::where('roundid', $request->roundid ?? null)->get()->first();

        if (empty($round)) {
            return redirect('/admin/rounds')->with('failure', 'Unable to update');
        }

        $round->label          = ucwords($validated['label']);
        $round->organisationid = intval($validated['organisationid']);
        $round->code           = !empty($validated['code'])       ? strtolower($validated['code']) : null;
        $round->dist1          = !empty($validated['dist1'])      ? intval($validated['dist1'])    : null;
        $round->dist1max       = !empty($validated['dist1max'])   ? intval($validated['dist1max']) : null;
        $round->dist2          = !empty($validated['dist2'])      ? intval($validated['dist2'])    : null;
        $round->dist2max       = !empty($validated['dist2max'])   ? intval($validated['dist2max']) : null;
        $round->dist3          = !empty($validated['dist3'])      ? intval($validated['dist3'])    : null;
        $round->dist3max       = !empty($validated['dist3max'])   ? intval($validated['dist3max']) : null;
        $round->dist4          = !empty($validated['dist4'])      ? intval($validated['dist4'])    : null;
        $round->dist4max       = !empty($validated['dist4max'])   ? intval($validated['dist4max']) : null;
        $round->totalmax       = !empty($validated['totalmax'])   ? intval($validated['totalmax']) : null;
        $round->visible        = !empty($validated['visible'])    ? 1 : 0;
        $round->matchplay        = !empty($validated['matchplay'])    ? 1 : 0;
        $round->retired        = !empty($validated['retired'])    ? 1 : 0;
        $round->type           = !empty($validated['type'])       ? strtolower($validated['type'])  : 'o';

        $errors = [];
        if (!empty($validated['anz_record_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_id'])) {
                $round->anz_record_id = $validated['anz_record_id'];
            }
            else {
                $errors[] = 'Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist1_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_dist1_id'])) {
                $round->anz_record_dist1_id = $validated['anz_record_dist1_id'];
            }
            else {
                $errors[] = 'Distance 1 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist2_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_dist2_id'])) {
                $round->anz_record_dist2_id = $validated['anz_record_dist2_id'];
            }
            else {
                $errors[] = 'Distance 2 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist3_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_dist3_id'])) {
                $round->anz_record_dist3_id = $validated['anz_record_dist3_id'];
            }
            else {
                $errors[] = 'Distance 3 Anz Record has existing mapping';
            }
        }

        if (!empty($validated['anz_record_dist4_id'])) {
            if ( ! $this->hasExistingRecordMapping($round->roundid, $validated['anz_record_dist4_id'])) {
                $round->anz_record_dist4_id = $validated['anz_record_dist4_id'];
            }
            else {
                $errors[] = 'Distance 4 Anz Record has existing mapping';
            }
        }

        if ($errors) {
            return back()->with('failure', $errors);
        }

        $round->save();


        return redirect('/admin/rounds')->with('success', 'Round Updated!');

    }

    protected function hasExistingRecordMapping(int $roundId, int $recordId)
    {
        $round = Round::where('anz_record_id', $recordId)
            ->orWhere('anz_record_dist1_id', $recordId)
            ->orWhere('anz_record_dist2_id', $recordId)
            ->orWhere('anz_record_dist3_id', $recordId)
            ->orWhere('anz_record_dist4_id', $recordId)
            ->first();

        if (!$round) {
            return false;
        }

        return $roundId && $round->roundid != $roundId;
    }


}
