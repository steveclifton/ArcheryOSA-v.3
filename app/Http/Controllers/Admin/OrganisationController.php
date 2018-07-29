<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOrganisation;
use App\Http\Requests\Admin\UpdateOrganisation;
use App\Models\Organisation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Request;

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


    public function getUpdateView(Request $request)
    {
        $organisationid = $request->organisationid ?? null;
        if (empty($organisationid)) {
            return redirect('/admin/organisations');
        }

        $organisation = Organisation::where('organisationid', $organisationid)->get()->first();

        return view('admin.organisations.update', compact('organisation'));

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

        return redirect('/admin/organisations')->with('success', 'Organisation Created!');


    }

    public function updateOrganisation(UpdateOrganisation $request)
    {
        $validated = $request->validated();

        $organisation = Organisation::where('organisationid', $request->organisationid ?? null)->get()->first();

        if (empty($organisation)) {
            return redirect('/admin/organisations')->with('failure', 'Unable to update');
        }

        $organisation->label          = ucwords($validated['label']);
        $organisation->description    = !empty($validated['description']) ? $validated['description'] : null;
        $organisation->phone          = !empty($validated['phone']) ? $validated['phone'] : null;
        $organisation->contactname    = !empty($validated['contactname']) ? $validated['contactname'] : null;
        $organisation->url            = !empty($validated['url']) ? $validated['url'] : null;
        $organisation->email          = !empty($validated['email']) ? $validated['email'] : null;
        $organisation->visible        = !empty($validated['visible']) ? 1 : 0;
        $organisation->save();

        return redirect('/admin/organisations')->with('success', 'Organisation Updated!');


    }


}
