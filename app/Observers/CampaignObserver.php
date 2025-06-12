<?php

namespace App\Observers;

use App\Models\Campaign;
use App\Services\NotificationService;

class CampaignObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Campaign $campaign)
    {
        $this->notificationService->campaignCreated($campaign);
    }

    public function updated(Campaign $campaign)
    {
        if ($campaign->wasChanged('status')) {
            $status = $campaign->status;
            $message = '';
            $color = 'info';

            switch ($status) {
                case 'Active':
                    $message = "تم تفعيل الحملة {$campaign->page_name}";
                    $color = 'success';
                    break;
                case 'Paused':
                    $message = "تم إيقاف الحملة {$campaign->page_name} مؤقتاً";
                    $color = 'warning';
                    break;
                case 'Completed':
                    $message = "اكتملت الحملة {$campaign->page_name}";
                    $color = 'success';
                    break;
            }

            if ($message) {
                $this->notificationService->sendToUsersWithPermission('access meym', 'campaign_status_changed',
                    'تغيير حالة الحملة',
                    $message,
                    ['campaign_id' => $campaign->campaign_id],
                    ['color' => $color]
                );
            }
        }
    }
}
