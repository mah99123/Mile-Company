<?php

namespace App\Http\Controllers;

use App\Models\LoginAttempt;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SecurityController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::whereNotNull('email_verified_at')->count(),
            'recent_logins' => LoginAttempt::where('status', 'success')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
            'failed_attempts' => LoginAttempt::where('status', 'failed')
                ->where('created_at', '>=', now()->subDays(7))
                ->count(),
        ];

        // Get recent login history for the current user - using user_id instead of email
        $loginHistory = LoginAttempt::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $user = Auth::user();

        return view('security.index', compact('stats', 'loginHistory', 'user'));
    }

    public function activityLog()
    {
        $activities = ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('security.activity-log', compact('activities'));
    }

    public function loginAttempts()
    {
        $attempts = LoginAttempt::orderBy('created_at', 'desc')
            ->paginate(20);

        return view('security.login-attempts', compact('attempts'));
    }

    public function settings()
    {
        return view('security.settings');
    }

    public function updateSettings(Request $request)
    {
        return back()->with('success', 'تم تحديث إعدادات الأمان بنجاح');
    }

    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($validated['current_password'], Auth::user()->password)) {
            return back()->with('error', 'كلمة المرور الحالية غير صحيحة');
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
            'password_changed_at' => now()
        ]);

        return back()->with('success', 'تم تغيير كلمة المرور بنجاح');
    }

    public function enable2FA(Request $request)
    {
        $user = Auth::user();
        $user->update(['two_factor_enabled' => true]);

        return back()->with('success', 'تم تفعيل المصادقة الثنائية بنجاح');
    }

    public function disable2FA(Request $request)
    {
        $user = Auth::user();
        $user->update(['two_factor_enabled' => false]);

        return back()->with('success', 'تم تعطيل المصادقة الثنائية بنجاح');
    }

    public function logoutAllDevices(Request $request)
    {
        Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'تم تسجيل الخروج من جميع الأجهزة الأخرى');
    }
}
