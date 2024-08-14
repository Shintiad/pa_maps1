<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function showUser() {
        $user = User::where('role', 0)->get();
        return view("pages.user", compact("user"));
    }
}
