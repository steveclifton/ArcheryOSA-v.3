<?php

namespace App\Http\Controllers\Admin;

use App\Role;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{
    /**
     * Creates a set of roles in the database
     *  - Used to initilize the dataset
     * @return bool
     */
    public function createRoles()
    {
        $roles = [
            'master' => 'Has access to areas of the site',
            'admin' => 'Has access to most areas of the site',
            'coordinator' => 'Has access to their own event access',
            'user' => 'Regular user',
        ];


        foreach ($roles as $label => $details) {
            $r = new Role();
            $r->label = $label;
            $r->details = $details;
            $r->save();
        }

    }


    /**
     * Adds additional roles to the database
     */
    public function addRole()
    {
        // Add new here
    }

}
