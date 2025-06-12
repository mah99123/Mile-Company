<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\InstallmentPayment;
use App\Services\NotificationService;

class CheckOverdueInstallments extends Command
{
    protected $signature = 'installments:check-overdue';
    protected $description = 'Check for overdue installments and send notifications';

    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $overdueInstallments = InstallmentPayment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->get();

        foreach ($overdueInstallments as $installment) {
            $this->notificationService->overdueInstallment($installment);
        }

        $this->info("تم فحص {$overdueInstallments->count()} قسط متأخر");
    }
}
