<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visitor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\VisitorArrivalMail;

class VisitorController extends Controller
{
    public function showForm()
    {
        return view('visitor.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'          => 'required|string|max:255',
            'last_name'           => 'required|string|max:255',
            'department'          => 'required|string|max:255',
            'company_affiliation' => 'nullable|string|max:255',
            'contact_person'      => 'required|string|max:255',
            'contact_info'        => 'nullable|email|max:255',
            'purpose'             => 'required|string|max:255',
            'other_purpose'       => 'nullable|string|max:255',
            'additional_notes'    => 'nullable|string|max:500',
        ]);

        $lastVisitor = Visitor::latest('id')->first();
        $nextId = $lastVisitor ? $lastVisitor->id + 1 : 1;
        $gatepassNo = 'PASS-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        date_default_timezone_set('Asia/Manila');
        $currentTime = Carbon::now();
        $formattedTime = $currentTime->format('h:i A'); 

            $finalPurpose = $validated['purpose'] === 'Other'
            ? $validated['other_purpose']
            : $validated['purpose'];

        $visitor = Visitor::create([
            'gatepass_no'         => $gatepassNo,
            'first_name'          => $validated['first_name'],
            'last_name'           => $validated['last_name'],
            'department'          => $validated['department'],
            'company_affiliation' => $validated['company_affiliation'] ?? null,
            'contact_person'      => $validated['contact_person'],
            'contact_info'        => $validated['contact_info'] ?? null,
            'purpose'             => $finalPurpose,
            'status'              => 'Inside',
            'time_in'             => $currentTime,
]);

        return redirect()->back()->with([
            'success'      => "Visitor added successfully!",
            'gatepass_no'  => $gatepassNo,
            'time_in'      => $formattedTime,
        ]);
    }
public function sendEmail($id)
{
    $visitor = Visitor::findOrFail($id);

    if (!empty($visitor->contact_info)) {
        try {
            Mail::to($visitor->contact_info)->send(new VisitorArrivalMail($visitor));

            return response()->json([
                'success' => true,
                'message' => '✅ Email sent successfully to ' . $visitor->contact_info,
            ]);
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => '❌ Failed to send email. Check mail settings.',
            ], 500);
        }
    }

    return response()->json([
        'success' => false,
        'message' => '❌ No valid email found for this visitor.',
    ], 400);
}

    public function timeOut($id)
    {
        $visitor = Visitor::findOrFail($id);
        date_default_timezone_set('Asia/Manila');
        $currentTime = Carbon::now();

        $visitor->update([
            'status'  => 'Timed Out',
            'time_out' => $currentTime,
        ]);

        return back()->with('success', 'Visitor has been timed out successfully!');
    }

public function reject($id)
{
    $visitor = Visitor::find($id);

    if (!$visitor) {
        return redirect()->back()->with('error', 'Visitor not found.');
    }

    $visitor->delete();

    return redirect()->back()->with('success', 'Visitor has been rejected and deleted.');
}
}