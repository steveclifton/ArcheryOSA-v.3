<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TournamentController extends Controller
{


    /**
     * GET Methods
     */

    /**
     * Returned main view
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function get()
    {
        return view('admin.tournaments.tournaments');
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        dd('here');
    }

}
