<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUpdate extends Model
{
    use HasFactory;

    protected $primaryKey = 'update_id';

    protected $fillable = [
        'campaign_id',
        'update_text',
        'update_date',
    ];

    protected $casts = [
        'update_date' => 'datetime',
    ];

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
}
