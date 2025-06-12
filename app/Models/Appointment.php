<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'appointment_date',
        'location',
        'attendees',
        'priority',
        'status',
        'reminder_minutes',
        'reminder_sent',
        'reminder_type',
        'user_id',
        'created_by',
        'reminder_settings'
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'reminder_sent' => 'boolean',
        'reminder_settings' => 'array'
    ];

    // العلاقات
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // الدوال المساعدة
    public function isUpcoming()
    {
        return $this->appointment_date > now() && $this->status === 'scheduled';
    }

    public function isPast()
    {
        return $this->appointment_date < now();
    }

    public function isToday()
    {
        return $this->appointment_date->isToday();
    }

    public function needsReminder()
    {
        if ($this->reminder_sent || $this->status !== 'scheduled') {
            return false;
        }

        $reminderTime = $this->appointment_date->subMinutes($this->reminder_minutes);
        return now() >= $reminderTime;
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => 'danger',
            'medium' => 'warning',
            'low' => 'success',
            default => 'primary'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'scheduled' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            'postponed' => 'warning',
            default => 'secondary'
        };
    }
}
