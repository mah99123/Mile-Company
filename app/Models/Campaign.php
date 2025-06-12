<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $primaryKey = 'campaign_id';

    protected $fillable = [
        'page_name',
        'owner_name',
        'specialization',
        'budget_total',
        'start_date',
        'end_date',
        'launch_date',
        'stop_date',
        'notes',
        'created_by',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'launch_date' => 'date',
        'stop_date' => 'date',
        'budget_total' => 'decimal:2',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class, 'campaign_id');
    }

    public function whatsappThreads()
    {
        return $this->hasMany(WhatsappThread::class, 'campaign_id');
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date);
    }

    public function getIsEndingSoonAttribute()
    {
        return $this->end_date->diffInDays(now()) <= 7;
    }
}
