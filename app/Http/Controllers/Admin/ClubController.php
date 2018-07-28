<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateClub;
use App\Models\Club;
use App\Models\Organisation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ClubController extends Controller
{



    /******************************************************************************
     * GET Requests
     ******************************************************************************/

    /**
     * Returns the main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        $clubs = Club::get();
        return view('admin.clubs.clubs', compact('clubs'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        $organisations = Organisation::where('visible', 1)->get();

        return view('admin.clubs.create', compact('organisations'));
    }








    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createClub(CreateClub $request)
    {
        $validated = $request->validated();

        $club = new Club();
        $club->label          = ucwords($validated['label']);
        $club->organisationid = intval($validated['organisationid']);
        $club->description    = !empty($validated['description']) ? $validated['description'] : null;
        $club->phone          = !empty($validated['phone']) ? $validated['phone'] : null;
        $club->contactname    = !empty($validated['contactname']) ? $validated['contactname'] : null;
        $club->address        = !empty($validated['address']) ? $validated['address'] : null;
        $club->suburb         = !empty($validated['suburb']) ? $validated['suburb'] : null;
        $club->city           = !empty($validated['city']) ? $validated['city'] : null;
        $club->country        = in_array($validated['country'], ['nz', 'au']) ? strtoupper($validated['country']) : 'Other';
        $club->url            = !empty($validated['url']) ? $validated['url'] : null;
        $club->email          = !empty($validated['email']) ? $validated['email'] : null;
        $club->visible        = !empty($validated['visible']) ? 1 : 0;
        $club->createdby      = Auth::id();
        $club->save();

        return redirect('/admin/clubs')->with('success', 'Club Created!');


    }

}
