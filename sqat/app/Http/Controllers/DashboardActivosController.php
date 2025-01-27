<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardActivosController extends Controller
{
    public function index()
    {

        return view('dashboardActivos');
    }
}
