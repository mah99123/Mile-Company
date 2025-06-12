<?php

namespace App\Observers;

use App\Models\InstallmentPayment;
use App\Services\NotificationService;

class InstallmentPaymentObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updated(InstallmentPayment $installment)
    {
        if ($installment->wasChanged('status') && $installment->status === 'paid') {
            $this->notificationService->installmentPaid($installment);
        }
    }
}
