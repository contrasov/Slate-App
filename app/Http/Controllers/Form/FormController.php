<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FormController extends Controller
{
    public function create(Request $request){
        return Inertia::render('forms/Form');
    }
}
