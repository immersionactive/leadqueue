<?php

namespace App\Http\Controllers\Backend;

use Log;
use App\Http\Controllers\Controller;

/**
 * Class DashboardController.
 */
class DashboardController extends Controller
{
    /**
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Log::info('Viewed dashboard');
        return view('backend.dashboard');
    }
}
