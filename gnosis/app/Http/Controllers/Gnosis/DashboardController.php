<?php

namespace App\Http\Controllers\Gnosis;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('gnosis.layouts.dashboard-index');
    }
}
