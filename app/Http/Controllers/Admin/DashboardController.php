<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __invoke(AnalyticsController $analytics)
    {
        return $analytics->dashboard();
    }
}
