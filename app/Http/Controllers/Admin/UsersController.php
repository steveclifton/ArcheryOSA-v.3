<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function get()
    {
        if (Auth::id() !== 1) {
            redirect('/');
        }

        $users = User::all();

        return view('admin.users.users', compact('users'));
    }
}
