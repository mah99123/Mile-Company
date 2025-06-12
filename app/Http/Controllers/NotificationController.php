<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:view-notifications']);
    }

    /**
     * عرض جميع الإشعارات للمستخدم الحالي
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()
                            ->orderBy('created_at', 'desc')
                            ->paginate(15);
        
        return view('notifications.index', compact('notifications'));
    }

    /**
     * الحصول على الإشعارات غير المقروءة (API)
     */
    public function getUnread()
    {
        $notifications = Auth::user()->notifications()
                            ->unread()
                            ->orderBy('created_at', 'desc')
                            ->limit(10)
                            ->get();

        return response()->json([
            'notifications' => $notifications,
            'count' => $notifications->count()
        ]);
    }

    /**
     * تحديث حالة الإشعار إلى مقروء
     */
    public function markAsRead($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * تحديث حالة جميع الإشعارات إلى مقروءة
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * حذف إشعار
     */
    public function destroy($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->delete();
        
        return response()->json(['success' => true]);
    }

    /**
     * إرسال إشعار جديد
     */
    public function send(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|string',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'sound' => 'nullable|string'
        ]);

        Notification::create([
            'type' => $request->type,
            'title' => $request->title,
            'message' => $request->message,
            'user_id' => $request->user_id,
            'icon' => $request->icon ?? 'fas fa-bell',
            'color' => $request->color ?? 'primary',
            'sound' => $request->sound
        ]);

        return response()->json(['success' => true]);
    }

    /**
     * الحصول على عدد الإشعارات غير المقروءة
     */
    public function getUnreadCount()
    {
        $count = Auth::user()->notifications()->unread()->count();
        return response()->json(['count' => $count]);
    }
}
