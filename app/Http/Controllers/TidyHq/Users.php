<?php

namespace App\Http\Controllers\TidyHq;

use App\Http\Controllers\Controller;
use App\Models\TidyHqContact;
use Illuminate\Http\Request;

class Users extends Controller
{
    public function get()
    {
        $contacts =  TidyHqContact::get();

        return view('admin.tidyhq.users', compact('contacts'));
    }


}
