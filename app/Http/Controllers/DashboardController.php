<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Show dashboard/Index  page. 
    public function index(Request $request)
    {

        return view('index');
    }

}
