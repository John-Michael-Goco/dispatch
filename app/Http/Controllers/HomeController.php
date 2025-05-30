<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Service;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $stats = [
            'total_incidents' => Incident::count(),
            'open_incidents' => Incident::where('status', 'open')->count(),
            'in_progress_incidents' => Incident::where('status', 'in_progress')->count(),
            'total_services' => Service::count(),
            'total_branches' => Branch::count(),
            'total_users' => User::count(),
            'recent_incidents' => Incident::with(['service', 'branch', 'reporter'])
                ->where('created_at', '>=', Carbon::now()->subDays(3))
                ->latest()
                ->take(5)
                ->get(),
            'branches' => Branch::where('status', 'active')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get(['name', 'latitude', 'longitude'])
        ];

        return view('home', compact('stats'));
    }
}
