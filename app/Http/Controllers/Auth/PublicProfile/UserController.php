<?php

namespace App\Http\Controllers\Auth\PublicProfile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function getPublicProfile(Request $request)
    {
        return view('profile.public.profile');
    }

}
