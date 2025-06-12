<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AppointmentController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:access admin']);
    }

    public function index(Request $request)
    {
        $query = Appointment::with(['user', 'creator']);

        // فلترة حسب التاريخ
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // فلترة حسب الأولوية
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $appointments = $query->orderBy('appointment_date', 'asc')->paginate(15);

        // إحصائيات
        $stats = [
            'total' => Appointment::count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
            'upcoming' => Appointment::where('appointment_date', '>', now())
                                   ->where('status', 'scheduled')->count(),
            'overdue' => Appointment::where('appointment_date', '<', now())
                                  ->where('status', 'scheduled')->count(),
        ];

        $users = User::all();

        return view('admin.appointments.index', compact('appointments', 'stats', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('admin.appointments.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'appointment_date' => 'required|date|after:now',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'user_id' => 'required|exists:users,id',
            'reminder_minutes' => 'required|integer|min:5|max:1440',
            'reminder_type' => 'required|in:notification,email,whatsapp'
        ]);

        $validated['created_by'] = Auth::id();

        $appointment = Appointment::create($validated);

        // إنشاء إشعار للمستخدم المعين
        $this->createNotification(
            $appointment->user_id,
            'appointment_created',
            'موعد جديد',
            "تم تعيين موعد جديد لك: {$appointment->title}",
            ['appointment_id' => $appointment->id],
            'fas fa-calendar-plus',
            'success',
            'notification.mp3'
        );

        return redirect()->route('admin.appointments.index')
                        ->with('success', 'تم إنشاء الموعد بنجاح');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load(['user', 'creator']);
        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $users = User::all();
        return view('admin.appointments.edit', compact('appointment', 'users'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'appointment_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'attendees' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:scheduled,completed,cancelled,postponed',
            'user_id' => 'required|exists:users,id',
            'reminder_minutes' => 'required|integer|min:5|max:1440',
            'reminder_type' => 'required|in:notification,email,whatsapp'
        ]);

        $appointment->update($validated);

        // إشعار بالتحديث
        $this->createNotification(
            $appointment->user_id,
            'appointment_updated',
            'تحديث موعد',
            "تم تحديث الموعد: {$appointment->title}",
            ['appointment_id' => $appointment->id],
            'fas fa-calendar-edit',
            'info',
            'update.mp3'
        );

        return redirect()->route('admin.appointments.index')
                        ->with('success', 'تم تحديث الموعد بنجاح');
    }

    public function destroy(Appointment $appointment)
    {
        // إشعار بالحذف
        $this->createNotification(
            $appointment->user_id,
            'appointment_deleted',
            'حذف موعد',
            "تم حذف الموعد: {$appointment->title}",
            [],
            'fas fa-calendar-times',
            'danger',
            'delete.mp3'
        );

        $appointment->delete();

        return redirect()->route('admin.appointments.index')
                        ->with('success', 'تم حذف الموعد بنجاح');
    }

    public function calendar()
    {
        $appointments = Appointment::with(['user', 'creator'])->get();
        
        $events = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'title' => $appointment->title,
                'start' => $appointment->appointment_date->toISOString(),
                'backgroundColor' => $this->getPriorityColor($appointment->priority),
                'borderColor' => $this->getPriorityColor($appointment->priority),
                'textColor' => '#fff',
                'extendedProps' => [
                    'description' => $appointment->description,
                    'location' => $appointment->location,
                    'priority' => $appointment->priority,
                    'status' => $appointment->status,
                    'user' => $appointment->user->name
                ]
            ];
        });

        return view('admin.appointments.calendar', compact('events'));
    }

    private function getPriorityColor($priority)
    {
        return match($priority) {
            'high' => '#dc3545',
            'medium' => '#ffc107',
            'low' => '#28a745',
            default => '#007bff'
        };
    }

    private function createNotification($userId, $type, $title, $message, $data = [], $icon = null, $color = 'primary', $sound = null)
    {
        Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'icon' => $icon,
            'color' => $color,
            'sound' => $sound
        ]);
    }
}
