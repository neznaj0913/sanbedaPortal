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

    public function index()
    {
        $codes = AccessCode::orderBy('created_at', 'desc')->get();
        return view('dashboard.generate-code', compact('codes'));
    }

    public function generate()
    {
        $code = strtoupper(Str::random(8));

        AccessCode::create([
            'code' => $code
        ]);

        return redirect()->back()->with('success', "New code generated: $code");
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string']);
    
        $code = AccessCode::where('code', $request->code)->first();
    
        if (!$code) {
            return redirect()->back()->withErrors(['code' => 'Invalid access code.']);
        }

        session([
            'access_granted' => true,
            'access_granted_at' => now()
        ]);
    
        return redirect()->route('visitor.form');
    }

    public function delete($id)
{
    AccessCode::where('id', $id)->delete();
    return redirect()->back()->with('success', "Access code deleted.");
}

}
