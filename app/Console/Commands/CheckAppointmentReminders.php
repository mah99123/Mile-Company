<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\Notification;
use Carbon\Carbon;

class CheckAppointmentReminders extends Command
{
    protected $signature = 'appointments:check-reminders';
    protected $description = 'Check for appointment reminders and send notifications';

    public function handle()
    {
        $this->info('Checking for appointment reminders...');

        $appointments = Appointment::where('status', 'scheduled')
                                 ->where('reminder_sent', false)
                                 ->get();

        $remindersSent = 0;

        foreach ($appointments as $appointment) {
            if ($appointment->needsReminder()) {
                $this->sendReminder($appointment);
                $appointment->update(['reminder_sent' => true]);
                $remindersSent++;
            }
        }

        $this->info("Sent {$remindersSent} appointment reminders.");
        return 0;
    }

    private function sendReminder(Appointment $appointment)
    {
        // إنشاء إشعار تذكير
        Notification::create([
            'type' => 'appointment_reminder',
            'title' => 'تذكير بموعد',
            'message' => "لديك موعد خلال {$appointment->reminder_minutes} دقيقة: {$appointment->title}",
            'data' => [
                'appointment_id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
                'location' => $appointment->location
            ],
            'user_id' => $appointment->user_id,
            'icon' => 'fas fa-clock',
            'color' => 'warning',
            'sound' => 'reminder.mp3'
        ]);

        $this->info("Reminder sent for appointment: {$appointment->title}");
    }
}
