<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProfileUserController extends Controller
{
    public function setting(Request $request){
        return Inertia::render('settings/Profile');
    }
}
