<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Sale;
use App\Services\WhatsAppService;

class CheckInstallmentReminders extends Command
{
    protected $signature = 'installments:check-reminders';
    protected $description = 'Check for upcoming installment payments and send reminders';

    public function handle()
    {
        $upcomingPayments = Sale::where('status', 'Active')
            ->where('next_installment_due_date', '<=', now()->addDays(2))
            ->where('next_installment_due_date', '>=', now())
            ->get();

        foreach ($upcomingPayments as $sale) {
            $this->sendReminder($sale);
        }

        $this->info("Processed {$upcomingPayments->count()} reminder(s)");
    }

    private function sendReminder(Sale $sale)
    {
        $message = "تذكير: لديك قسط مستحق بتاريخ {$sale->next_installment_due_date->format('Y-m-d')} بمبلغ {$sale->installment_amount} دينار للمنتج {$sale->product->name}";
        
        // Here you would integrate with WhatsApp API
        // WhatsAppService::sendMessage($sale->customer_phone, $message);
        
        $this->info("Reminder sent to {$sale->customer_name} ({$sale->customer_phone})");
    }
}
