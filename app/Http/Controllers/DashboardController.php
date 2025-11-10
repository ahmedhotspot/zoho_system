<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Offer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }
}
