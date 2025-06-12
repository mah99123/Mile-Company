<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\NotificationService;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Auth;

class SecurityNotifications
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // تحقق من محاولات الدخول المشبوهة
        if (Auth::check()) {
            $user = Auth::user();
            $currentIp = $request->ip();
            
            // إذا كان IP جديد للمستخدم
            $lastLogin = LoginAttempt::where('email', $user->email)
                ->where('status', 'success')
                ->where('ip_address', '!=', $currentIp)
                ->latest()
                ->first();

            if ($lastLogin && $lastLogin->created_at->diffInHours(now()) < 1) {
                $this->notificationService->suspiciousLogin($user, $currentIp);
            }
        }

        return $response;
    }
}
