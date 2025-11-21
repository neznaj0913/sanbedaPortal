<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\VisitorExport;
use Carbon\Carbon;

class VisitorHistoryController extends Controller
{
    public function index(Request $request)
    {
        $search    = $request->input('search');
        $status    = $request->input('status', 'all');
        $from_date = $request->input('from_date');
        $to_date   = $request->input('to_date');

        $query = Visitor::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('gatepass_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            if ($status === 'Inside') {
                $query->whereNotNull('time_in')->whereNull('time_out');
            } elseif ($status === 'Timed Out') {
                $query->whereNotNull('time_out');
            }
        }

        if (!empty($from_date) && !empty($to_date)) {
            $query->whereBetween('time_in', [
                Carbon::parse($from_date)->startOfDay(),
                Carbon::parse($to_date)->endOfDay(),
            ]);
        } elseif (!empty($from_date)) {
            $query->whereDate('time_in', '>=', Carbon::parse($from_date));
        } elseif (!empty($to_date)) {
            $query->whereDate('time_in', '<=', Carbon::parse($to_date));
        }

        $visitors = $query
            ->select(
                'id',
                'gatepass_no',
                'first_name',
                'last_name',
                'department',
                'purpose',
                'email',
                'time_in',
                'time_out',
                'status'
            )
            ->orderBy('time_in', 'desc')
            ->paginate(10);

        $visitors->appends($request->all());

        return view('dashboard.history', compact('visitors', 'search', 'status', 'from_date', 'to_date'));
    }

    public function export(Request $request)
    {
        $search    = $request->input('search');
        $status    = $request->input('status', 'all');
        $from_date = $request->input('from_date');
        $to_date   = $request->input('to_date');
        $today     = $request->boolean('today');

        $query = Visitor::query();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('gatepass_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('department', 'like', "%{$search}%")
                  ->orWhere('purpose', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($status !== 'all') {
            if ($status === 'Inside') {
                $query->whereNotNull('time_in')->whereNull('time_out');
            } elseif ($status === 'Timed Out') {
                $query->whereNotNull('time_out');
            }
        }

        if ($today) {
            $query->whereDate('time_in', Carbon::now('Asia/Manila')->toDateString());
        } else {
            if (!empty($from_date) && !empty($to_date)) {
                $query->whereBetween('time_in', [
                    Carbon::parse($from_date)->startOfDay(),
                    Carbon::parse($to_date)->endOfDay(),
                ]);
            } elseif (!empty($from_date)) {
                $query->whereDate('time_in', '>=', Carbon::parse($from_date));
            } elseif (!empty($to_date)) {
                $query->whereDate('time_in', '<=', Carbon::parse($to_date));
            }
        }

        $visitors = $query
            ->select(
                'id',
                'gatepass_no',
                'first_name',
                'last_name',
                'department',
                'purpose',
                'email',
                'time_in',
                'time_out',
                'status'
            )
            ->orderBy('time_in', 'desc')
            ->get();

        $fileName = $today
            ? 'visitor_history_today.xlsx'
            : 'visitor_history_' . now()->format('Y_m_d_His') . '.xlsx';

        return Excel::download(new VisitorExport($visitors), $fileName);
    }

    public function fetchVisitors()
    {
        $visitors = Visitor::select(
            'id',
            'gatepass_no',
            'first_name',
            'last_name',
            'department',
            'purpose',
            'time_in',
            'time_out',
            'status'
        )
        ->orderBy('gatepass_no', 'asc') 
        ->get();

        return response()->json(['visitors' => $visitors]);
    }
}
