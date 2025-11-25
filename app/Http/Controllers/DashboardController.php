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
        ->get()
        ->map(function ($v) {
            return [
                'hour_label' => sprintf("%02d:00", $v->hour),
                'count' => $v->count
            ];
        });

   $visitorsByPurpose = Visitor::selectRaw("
    CASE
        WHEN purpose IN (
            'Meeting',
            'Maintenance',
            'Delivery',
            'Interview',
            'Official Business'
        )
        THEN purpose
        ELSE 'Others'
    END as purpose_category,
    COUNT(*) as count
")
->groupBy('purpose_category')
->orderBy('count', 'desc')
->get();


    
    $otherPurposeCount = Visitor::where('purpose', 'Other')->count();

    $otherSpecificCount = Visitor::where('purpose', 'Other')
        ->whereNotNull('additional_notes')
        ->where('additional_notes', '!=', '')
        ->count();

    return view('dashboard.stats', compact(
        'totalVisitors',
        'currentlyInside',
        'checkedOut',
        'visitorsByHour',
        'visitorsByPurpose',
        'otherPurposeCount',
        'otherSpecificCount'
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
    ->map(function ($row) {
       
        $time = sprintf('%02d:00', $row->hour);   
        $formatted = date("h:i A", strtotime($time)); 

        return [
            'hour_label' => $formatted, 
            'count' => $row->count
        ];
    });

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
