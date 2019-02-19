<?php

namespace App\Http\Controllers\Admin;

use App\Models\DivisionAge;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionAgesController extends Controller
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
        $divisions = DivisionAge::get();

        return view('admin.divisionages.divisionsages', compact('divisions'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.divisionages.create');
    }


    public function getUpdateView(Request $request)
    {
        $divisionid = $request->divisionagesid ?? null;

        if (empty($divisionid)) {
            return redirect('/admin/divisions');
        }

        $division = DivisionAge::where('divisionagesid', $divisionid)->get()->first();

        return view('admin.divisionages.update', compact('division'));

    }



    /******************************************************************************
     * POST Requests
     ******************************************************************************/
    public function createDivision(Request $request)
    {
        $division = new DivisionAge();
        $division->label          = $request->label;
        $division->description    = $request->description;

        $division->save();

        return redirect('/admin/divisionages')->with('success', 'Division Created!');
    }


    public function updateDivision(Request $request)
    {

        $division = DivisionAge::where('divisionagesid', $request->divisionagesid ?? null)->get()->first();

        if (empty($division)) {
            return redirect('/admin/divisionages')->with('failure', 'Unable to update');
        }

        $division->label          = $request->label;
        $division->description    = $request->description;

        $division->save();

        return redirect('/admin/divisionages')->with('success', 'Division Age Updated!');


    }



}
