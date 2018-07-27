<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateDivision;
use App\Models\Division;
use App\Models\Organisation;
use Illuminate\Http\Request;
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


        $club = new Division();
        $club->label          = ucwords($validated['label']);
        $club->organisationid = intval($validated['organisationid']);
        $club->code           = !empty($validated['code'])            ? strtolower($validated['code']) : null;
        $club->description    = !empty($validated['description'])     ? strtolower($validated['description']) : null;
        $club->visible        = !empty($validated['visible'])         ? 1 : 0;
        $club->createdby      = Auth::id();
        $club->save();

        return redirect('/admin/divisions');


    }


}
