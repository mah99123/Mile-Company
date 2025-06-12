<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsappThread extends Model
{
    use HasFactory;

    protected $primaryKey = 'thread_id';

    protected $fillable = [
        'campaign_id',
        'customer_whatsapp',
        'message_content',
        'message_date',
        'message_type',
    ];

    protected $casts = [
        'message_date' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
