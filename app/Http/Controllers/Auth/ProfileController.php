<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function get()
    {
        return view('profile.profile');
    }

    public function getMyDetails()
    {
        return view('profile.mydetails');
    }
}
