<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOrganisation;
use App\Models\Organisation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrganisationController extends Controller
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
        $organisations = Organisation::get();
        return view('admin.organisations.organisations', compact('organisations'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.organisations.create');

    }








    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createOrganisation(CreateOrganisation $request)
    {
        $validated = $request->validated();

        $organisation = new Organisation();
        $organisation->label          = ucwords($validated['label']);
        $organisation->description    = !empty($validated['description']) ? $validated['description'] : null;
        $organisation->phone          = !empty($validated['phone']) ? $validated['phone'] : null;
        $organisation->contactname    = !empty($validated['contactname']) ? $validated['contactname'] : null;
        $organisation->url            = !empty($validated['url']) ? $validated['url'] : null;
        $organisation->email          = !empty($validated['email']) ? $validated['email'] : null;
        $organisation->visible        = !empty($validated['visible']) ? 1 : 0;
        $organisation->createdby      = Auth::id();
        $organisation->save();

        return redirect('/admin/organisations');


    }

}
