<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\UserUpdateProfile;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{







    /******************************************************************************
     * GET Requests
     ******************************************************************************/

    /**
     * Gets the users dashboard
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getDashboard()
    {
        return view('profile.profile');
    }

    /**
     * Gets the users details form
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getMyDetails()
    {
        return view('profile.mydetails');
    }





    /******************************************************************************
     * POST Requests
     ******************************************************************************/

    /**
     * Updates a Users Profile
     * @param UserUpdateProfile $request
     * @return redirect
     */
    public function updateProfile(UserUpdateProfile $request)
    {
        $validated = $request->validated();

        $user = Auth::user();
        $user->firstname   = $validated['firstname'];
        $user->lastname    = $validated['lastname'];
        $user->phone       = $validated['phone'];
        $user->address     = $validated['address'];
        $user->city        = $validated['city'];
        $user->postcode    = $validated['postcode'];
        $user->postcode    = $validated['postcode'];
        $user->dateofbirth = $validated['dateofbirth'];

        $user->save();

        return redirect()->back()->with('success', 'Profile Updated');
    }



}
