<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubstationController extends Controller
{
    public function index()
    {
        return view('substations');
    }
}
