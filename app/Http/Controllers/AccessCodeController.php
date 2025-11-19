<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessCode;
use Illuminate\Support\Str;

class AccessCodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Show generated codes
    public function index()
    {
        $codes = AccessCode::orderBy('created_at', 'desc')->get();
        return view('dashboard.generate-code', compact('codes'));
    }

    // Generate new code
    public function generate()
    {
        $code = strtoupper(Str::random(8));

        AccessCode::create([
            'code' => $code
        ]);

        return redirect()->back()->with('success', "New code generated: $code");
    }

    // Verify (no expiration + no used flag)
    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
    
        $code = AccessCode::where('code', $request->code)->first();
    
        if (!$code) {
            return redirect()->back()->withErrors(['code' => 'Invalid access code.']);
        }

        // Grant 1-month access
        session([
            'access_granted' => true,
            'access_granted_at' => now()
        ]);
    
        return redirect()->route('visitor.form');
    }

    // Delete a generated code
    public function delete($id)
{
    AccessCode::where('id', $id)->delete();
    return redirect()->back()->with('success', "Access code deleted.");
}

}
