<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateDivision;
use App\Models\Division;
use App\Models\Organisation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $divisions = Division::get();
        return view('admin.divisions.divisions', compact('divisions'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        $organisations = Organisation::get();
        return view('admin.divisions.create', compact('organisations'));
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
        $division->save();

        return redirect('/admin/divisions')->with('success', 'Division Updated!');


    }


}
