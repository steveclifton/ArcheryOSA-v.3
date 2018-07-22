<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        return view('admin.organisations.organisations');
    }


    /**
     * Returns the create view
     */
    public function getCreateView()
    {
        return view('admin.organisations.create');

    }

}
