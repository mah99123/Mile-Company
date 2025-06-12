<?php

namespace App\Observers;

use App\Models\Sale;
use App\Services\NotificationService;

class SaleObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function created(Sale $sale)
    {
        $this->notificationService->newSale($sale);
    }

    public function updated(Sale $sale)
    {
        if ($sale->wasChanged('status') && $sale->status === 'Completed') {
            $this->notificationService->sendToUsersWithPermission('access phonetech', 'sale_completed',
                'اكتملت المبيعة',
                "اكتملت المبيعة للعميل {$sale->customer_name}",
                ['sale_id' => $sale->invoice_id],
                ['color' => 'success']
            );
        }
    }
}
