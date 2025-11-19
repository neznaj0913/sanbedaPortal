<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\OTPController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\VisitorHistoryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AccessCodeController;

// ===============================
// ✅ Fetch Visitors (AJAX)
// ===============================
Route::get('/fetch-visitors', [DashboardController::class, 'fetchVisitors'])->name('fetch.visitors');

// Redirect root to register page
Route::get('/', function () {
    return redirect()->route('register.form');
});

// ===============================
// ✅ Registration Routes
// ===============================
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// ===============================
// ✅ OTP (AJAX) Routes
// ===============================
Route::post('/send-otp', [OTPController::class, 'sendOtp'])->name('otp.send');
Route::post('/verify-otp', [OTPController::class, 'checkOtp'])->name('otp.verify');

// ===============================
// ✅ Auth Routes
// ===============================
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.view');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ===============================
// ✅ Dashboard Routes
// ===============================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/stats', [DashboardController::class, 'stats'])->name('dashboard.stats');
    Route::get('/dashboard/history', [VisitorHistoryController::class, 'index'])->name('dashboard.history');

    // ✅ Gatepass Access Code Management
    Route::get('/dashboard/generate-code', [AccessCodeController::class, 'index'])->name('accesscode.index');
    Route::post('/dashboard/generate-code', [AccessCodeController::class, 'generate'])->name('accesscode.generate');
});

// ===============================
// ✅ Visitor Code Verification Routes
// ===============================
Route::get('/visitor/code', function () {
    return view('visitor.enter-code');
})->name('visitor.code');

Route::post('/visitor/code', [AccessCodeController::class, 'verify'])->name('accesscode.verify');

// ===============================
// ✅ Visitor Form Routes Protected by Access Middleware
// ===============================
Route::middleware(['visitor.access'])->group(function () {
    Route::get('/visitor', [VisitorController::class, 'showForm'])->name('visitor.form');
    Route::post('/visitor', [VisitorController::class, 'store'])->name('visitor.store');
});

// ===============================
// ✅ Visitor Admin Actions
// ===============================
Route::post('/visitor/{id}/send-email', [VisitorController::class, 'sendEmail'])->name('visitor.sendEmail');
Route::get('/visitor/{id}/time-out', [VisitorController::class, 'timeOut'])->name('visitor.timeOut');
Route::delete('/visitor/{id}/reject', [VisitorController::class, 'reject'])->name('visitor.reject');

// ===============================
// ✅ Export to Excel
// ===============================
Route::get('/history/export', [VisitorHistoryController::class, 'export'])->name('export.visitors');

// ===============================
// ✅ Forgot & Reset Password Routes
// ===============================
Route::controller(ForgotPasswordController::class)->group(function () {
    Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
    Route::post('/forgot-password', 'sendResetLinkEmail')->name('password.email');
});

Route::controller(ResetPasswordController::class)->group(function () {
    Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
    Route::post('/reset-password', 'reset')->name('password.update');
});

// ===============================
// ✅ Visitor History Auto-Refresh
// ===============================
Route::get('/fetch-history-visitors', [VisitorHistoryController::class, 'fetchVisitors'])
    ->name('fetch.history.visitors');
Route::delete('/dashboard/code/{id}', [App\Http\Controllers\AccessCodeController::class, 'delete'])
     ->name('accesscode.delete');
