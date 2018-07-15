<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    public function createSiteRoles()
    {
        $rc = new RoleController();
        $rc->createRoles();

        return redirect('/');
    }
}
