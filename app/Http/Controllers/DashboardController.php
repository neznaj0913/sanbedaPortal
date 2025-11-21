<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Visitor;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $visitors = Visitor::orderBy('company_affiliation', 'desc')->paginate();

        return view('dashboard.user', compact('user', 'visitors'));
    }

    public function stats()
    {
        $totalVisitors = Visitor::count();
        $currentlyInside = Visitor::where('status', 'Inside')->count();
        $checkedOut = Visitor::where('status', 'Checked Out')->count();

        $visitorsByHour = Visitor::selectRaw('HOUR(time_in) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        $visitorsByCompany = Visitor::selectRaw('company_affiliation, COUNT(*) as count')
    ->groupBy('company_affiliation')
    ->get();


          return view('dashboard.stats', compact(
        'totalVisitors',
        'currentlyInside',
        'checkedOut',
        'visitorsByHour',
        'visitorsByCompany' 
    ));
}

   
    public function fetchVisitors()
    {
        $visitors = Visitor::orderBy('company_affiliation', 'desc')->get();

        $totalVisitors = Visitor::count();
        $currentlyInside = Visitor::where('status', 'Inside')->count();
        $checkedOut = Visitor::where('status', 'Checked Out')->count();

        $visitorsByHour = Visitor::selectRaw('HOUR(time_in) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get()
            ->map(fn($v) => [
                'hour_label' => sprintf("%02d:00", $v->hour),
                'count' => $v->count
            ]);

        $visitorsByPurpose = Visitor::selectRaw('purpose, COUNT(*) as count')
            ->groupBy('purpose')
            ->get();

        return response()->json([
            'visitors' => $visitors,
            'totalVisitors' => $totalVisitors,
            'currentlyInside' => $currentlyInside,
            'checkedOut' => $checkedOut,
            'visitorsByHour' => $visitorsByHour,
            'visitorsByPurpose' => $visitorsByPurpose
        ]);
    }
}
