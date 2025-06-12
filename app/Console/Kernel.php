<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\CheckInstallmentReminders::class,
        Commands\CheckAppointmentReminders::class,
        Commands\CheckOverdueInstallments::class,
        Commands\CheckCampaignEnding::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // فحص تذكيرات الأقساط يومياً في 9 صباحاً
        $schedule->command('installments:check-reminders')
                 ->dailyAt('09:00')
                 ->timezone('Asia/Baghdad');

        // فحص تذكيرات المواعيد كل 5 دقائق
        $schedule->command('appointments:check-reminders')
                 ->everyFiveMinutes();

        // فحص الأقساط المتأخرة يومياً في 10 صباحاً
        $schedule->command('installments:check-overdue')
                 ->dailyAt('10:00')
                 ->timezone('Asia/Baghdad');

        // فحص الحملات التي تنتهي قريباً يومياً في 8 صباحاً
        $schedule->command('campaigns:check-ending')
                 ->dailyAt('08:00')
                 ->timezone('Asia/Baghdad');

        // نسخ احتياطي يومي في منتصف الليل
        $schedule->command('backup:run')
                 ->dailyAt('00:00')
                 ->timezone('Asia/Baghdad');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
