<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateDivision;
use App\Http\Requests\Admin\UpdateDivision;
use App\Models\Division;
use App\Models\DivisionAge;
use App\Models\Organisation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DivisionController extends Controller
{

    /**
     * GET Methods
     */

    /**
     * Returns main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $divisions = Division::orderby('bowtype')->get();

        return view('admin.divisions.divisions', compact('divisions'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        $organisations = Organisation::get();
        $divisionages = DivisionAge::get();

        return view('admin.divisions.create', compact('organisations', 'divisionages'));
    }


    public function getUpdateView(Request $request)
    {
        $divisionid = $request->divisionid ?? null;

        if (empty($divisionid)) {
            return redirect('/admin/divisions');
        }

        $division = Division::where('divisionid', $divisionid)->get()->first();
        $organisations = Organisation::get();
        $divisionages = DivisionAge::get();

        return view('admin.divisions.update', compact('division', 'organisations', 'divisionages'));

    }



    /******************************************************************************
     * POST Requests
     ******************************************************************************/
    public function createDivision(CreateDivision $request)
    {
        $validated = $request->validated();

        $division = new Division();
        $division->label          = ucwords($validated['label']);
        $division->organisationid = intval($validated['organisationid']);
        $division->code           = !empty($validated['code'])            ? strtolower($validated['code']) : null;
        $division->description    = !empty($validated['description'])     ? strtolower($validated['description']) : null;
        $division->visible        = !empty($validated['visible'])         ? 1 : 0;
        $division->createdby      = Auth::id();
        $division->bowtype        = !empty($validated['bowtype'])         ? strtolower($validated['bowtype']) : null;
        $division->age            = !empty($validated['age'])             ? strtolower($validated['age']) : null;
        $division->save();

        return redirect('/admin/divisions')->with('success', 'Division Created!');
    }


    public function updateDivision(UpdateDivision $request)
    {
        $validated = $request->validated();


        $division = Division::where('divisionid', $request->divisionid ?? null)->get()->first();

        if (empty($division)) {
            return redirect('/admin/divisions')->with('failure', 'Unable to update');
        }

        $division->label          = ucwords($validated['label']);
        $division->organisationid = intval($validated['organisationid']);
        $division->code           = !empty($validated['code'])            ? strtolower($validated['code']) : null;
        $division->description    = !empty($validated['description'])     ? strtolower($validated['description']) : null;
        $division->visible        = !empty($validated['visible'])         ? 1 : 0;
        $division->bowtype        = !empty($validated['bowtype'])         ? strtolower($validated['bowtype']) : null;
        $division->age            = !empty($validated['age'])             ? strtolower($validated['age']) : null;


        $division->save();

        return redirect('/admin/divisions')->with('success', 'Division Updated!');


    }


    private function getBowType($type)
    {

        switch (1) {
            case (stripos($type, 'compound') > 0) :
                return 'compound';
                break;
            case (stripos($type, 'recurve') > 0) :
                return 'recurve';
                break;
            case (stripos($type, 'barebow') > 0) :
                return 'barebow';
                break;
            case (stripos($type, 'longbow') > 0) :
                return 'longbow';
                break;
            case (stripos($type, 'crossbow') > 0) :
                return 'crossbow';
                break;
        }

        return 'other';
    }


}
