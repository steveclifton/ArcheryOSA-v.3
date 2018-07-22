<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return view('admin.divisions.divisions');
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.divisions.create');

    }
}
