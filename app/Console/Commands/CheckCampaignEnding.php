<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Services\NotificationService;

class CheckCampaignEnding extends Command
{
    protected $signature = 'campaigns:check-ending';
    protected $description = 'Check for campaigns ending soon';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $endingSoon = Campaign::where('status', 'Active')
            ->whereBetween('end_date', [now(), now()->addDays(3)])
            ->get();

        foreach ($endingSoon as $campaign) {
            $this->notificationService->campaignEnding($campaign);
        }

        $this->info("تم فحص {$endingSoon->count()} حملة تنتهي قريباً");
    }
}
