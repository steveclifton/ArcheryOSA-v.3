<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClubController extends Controller
{



    /**
     * GET Methods
     */


    /**
     * Returns the main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        return view('admin.clubs.clubs');
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.clubs.create');

    }
}
