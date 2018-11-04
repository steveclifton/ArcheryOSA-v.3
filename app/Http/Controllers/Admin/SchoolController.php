<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateSchool;
use App\Http\Requests\Admin\UpdateSchool;
use App\Models\Organisation;
use App\Models\School;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SchoolController extends Controller
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
        $schools = School::orderby('label')->get();
        return view('admin.schools.schools', compact('schools'));
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.schools.create');
    }

    public function getUpdateView(Request $request)
    {
        $schoolid = $request->schoolid ?? null;
        if (empty($schoolid)) {
            return redirect('/admin/schools');
        }

        $school = School::where('schoolid', $schoolid)->get()->first();

        return view('admin.schools.update', compact('school'));

    }








    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    public function createSchool(CreateSchool $request)
    {
        $validated = $request->validated();

        $school = new School();
        $school->label          = ucwords($validated['label']);
        $school->description    = !empty($validated['description']) ? $validated['description'] : null;
        $school->phone          = !empty($validated['phone']) ? $validated['phone'] : null;
        $school->contactname    = !empty($validated['contactname']) ? $validated['contactname'] : null;
        $school->address        = !empty($validated['address']) ? $validated['address'] : null;
        $school->suburb         = !empty($validated['suburb']) ? $validated['suburb'] : null;
        $school->city           = !empty($validated['city']) ? $validated['city'] : null;
        $school->country        = in_array($validated['country'], ['nz', 'au']) ? strtoupper($validated['country']) : 'Other';
        $school->url            = !empty($validated['url']) ? $validated['url'] : null;
        $school->email          = !empty($validated['email']) ? $validated['email'] : null;
        $school->visible        = !empty($validated['visible']) ? 1 : 0;
        $school->createdby      = Auth::id();
        $school->save();

        return redirect('/admin/schools')->with('success', 'School Created!');


    }

    public function updateSchool(UpdateSchool $request)
    {

        $validated = $request->validated();

        $school = School::where('schoolid', $request->schoolid ?? null)->get()->first();

        if (empty($school)) {
            return redirect('/admin/schools')->with('failure', 'Unable to update');
        }

        $school->label          = ucwords($validated['label']);
        $school->description    = !empty($validated['description']) ? $validated['description'] : null;
        $school->phone          = !empty($validated['phone']) ? $validated['phone'] : null;
        $school->contactname    = !empty($validated['contactname']) ? $validated['contactname'] : null;
        $school->address        = !empty($validated['address']) ? $validated['address'] : null;
        $school->suburb         = !empty($validated['suburb']) ? $validated['suburb'] : null;
        $school->city           = !empty($validated['city']) ? $validated['city'] : null;
        $school->country        = in_array($validated['country'], ['nz', 'au']) ? strtoupper($validated['country']) : 'Other';
        $school->url            = !empty($validated['url']) ? $validated['url'] : null;
        $school->email          = !empty($validated['email']) ? $validated['email'] : null;
        $school->visible        = !empty($validated['visible']) ? 1 : 0;

        $school->save();

        return redirect('/admin/schools')->with('success', 'School Updated!');


    }

}
