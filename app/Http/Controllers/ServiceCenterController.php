<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ServiceCenterController extends Controller
{
    public function index()
    {
        return view('service-centers');
    }
}
