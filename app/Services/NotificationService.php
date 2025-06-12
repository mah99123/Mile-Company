<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationService
{
    /**
     * إرسال إشعار لمستخدم واحد
     */
    public function sendToUser($userId, $type, $title, $message, $data = [], $options = [])
    {
        return Notification::create([
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'user_id' => $userId,
            'icon' => $options['icon'] ?? $this->getIconByType($type),
            'color' => $options['color'] ?? $this->getColorByType($type),
            'sound' => $options['sound'] ?? $this->getSoundByType($type)
        ]);
    }

    /**
     * إرسال إشعار لمجموعة من المستخدمين
     */
    public function sendToUsers($userIds, $type, $title, $message, $data = [], $options = [])
    {
        foreach ($userIds as $userId) {
            $this->sendToUser($userId, $type, $title, $message, $data, $options);
        }
    }

    /**
     * إرسال إشعار لجميع المستخدمين الذين لديهم صلاحية معينة
     */
    public function sendToUsersWithPermission($permission, $type, $title, $message, $data = [], $options = [])
    {
        $users = User::permission($permission)->pluck('id');
        $this->sendToUsers($users, $type, $title, $message, $data, $options);
    }

    /**
     * إرسال إشعار لجميع الأدمن
     */
    public function sendToAdmins($type, $title, $message, $data = [], $options = [])
    {
        $this->sendToUsersWithPermission('access admin', $type, $title, $message, $data, $options);
    }

    // إشعارات المبيعات
    public function newSale($sale)
    {
        $this->sendToUsersWithPermission('access phonetech', 'sale_created', 
            'مبيعة جديدة', 
            "تم إنشاء مبيعة جديدة للعميل {$sale->customer_name} بقيمة {$sale->total_amount} ريال",
            ['sale_id' => $sale->invoice_id, 'customer' => $sale->customer_name]
        );
    }

    public function overdueInstallment($installment)
    {
        $this->sendToUsersWithPermission('access phonetech', 'installment_overdue',
            'قسط متأخر',
            "القسط رقم {$installment->payment_id} متأخر عن موعده المحدد",
            ['installment_id' => $installment->payment_id],
            ['color' => 'danger', 'sound' => 'reminder']
        );
    }

    public function installmentPaid($installment)
    {
        $this->sendToUsersWithPermission('access phonetech', 'installment_paid',
            'تم دفع قسط',
            "تم دفع القسط بقيمة {$installment->amount_paid} ريال",
            ['installment_id' => $installment->payment_id],
            ['color' => 'success']
        );
    }

    // إشعارات المخزون
    public function lowStock($product)
    {
        $this->sendToUsersWithPermission('access phonetech', 'low_stock',
            'مخزون منخفض',
            "المنتج {$product->name} وصل لحد إعادة الطلب ({$product->quantity_in_stock} قطعة)",
            ['product_id' => $product->product_id],
            ['color' => 'warning', 'sound' => 'reminder']
        );
    }

    public function outOfStock($product)
    {
        $this->sendToUsersWithPermission('access phonetech', 'out_of_stock',
            'نفد المخزون',
            "المنتج {$product->name} نفد من المخزون",
            ['product_id' => $product->product_id],
            ['color' => 'danger', 'sound' => 'reminder']
        );
    }

    // إشعارات الحملات
    public function campaignCreated($campaign)
    {
        $this->sendToUsersWithPermission('access meym', 'campaign_created',
            'حملة جديدة',
            "تم إنشاء حملة جديدة: {$campaign->page_name}",
            ['campaign_id' => $campaign->campaign_id]
        );
    }

    public function campaignEnding($campaign)
    {
        $this->sendToUsersWithPermission('access meym', 'campaign_ending',
            'حملة تنتهي قريباً',
            "الحملة {$campaign->page_name} ستنتهي خلال 3 أيام",
            ['campaign_id' => $campaign->campaign_id],
            ['color' => 'warning', 'sound' => 'reminder']
        );
    }

    public function campaignBudgetLow($campaign)
    {
        $this->sendToUsersWithPermission('access meym', 'campaign_budget_low',
            'ميزانية الحملة منخفضة',
            "ميزانية الحملة {$campaign->page_name} أوشكت على النفاد",
            ['campaign_id' => $campaign->campaign_id],
            ['color' => 'warning']
        );
    }

    // إشعارات استيراد السيارات
    public function carImportCreated($carImport)
    {
        $this->sendToUsersWithPermission('access carimport', 'car_import_created',
            'استيراد سيارة جديد',
            "تم إضافة استيراد سيارة جديد برقم اللوط {$carImport->lot_number}",
            ['car_import_id' => $carImport->id]
        );
    }

    public function carShipped($carImport)
    {
        $this->sendToUsersWithPermission('access carimport', 'car_shipped',
            'تم شحن السيارة',
            "تم شحن السيارة برقم اللوط {$carImport->lot_number}",
            ['car_import_id' => $carImport->id],
            ['color' => 'info']
        );
    }

    public function carArrived($carImport)
    {
        $this->sendToUsersWithPermission('access carimport', 'car_arrived',
            'وصلت السيارة',
            "وصلت السيارة برقم اللوط {$carImport->lot_number}",
            ['car_import_id' => $carImport->id],
            ['color' => 'success']
        );
    }

    // إشعارات الأمان
    public function suspiciousLogin($user, $ip)
    {
        $this->sendToAdmins('security_alert',
            'محاولة دخول مشبوهة',
            "محاولة دخول مشبوهة للمستخدم {$user->name} من IP: {$ip}",
            ['user_id' => $user->id, 'ip' => $ip],
            ['color' => 'danger', 'sound' => 'delete']
        );
    }

    public function multipleFailedLogins($email, $ip)
    {
        $this->sendToAdmins('security_alert',
            'محاولات دخول فاشلة متعددة',
            "محاولات دخول فاشلة متعددة للإيميل {$email} من IP: {$ip}",
            ['email' => $email, 'ip' => $ip],
            ['color' => 'danger', 'sound' => 'delete']
        );
    }

    // إشعارات المحاسبة
    public function newJournalEntry($entry)
    {
        $this->sendToUsersWithPermission('access admin', 'journal_entry_created',
            'قيد محاسبي جديد',
            "تم إنشاء قيد محاسبي جديد بقيمة {$entry->amount}",
            ['entry_id' => $entry->id]
        );
    }

    // إشعارات المواعيد
    public function appointmentReminder($appointment)
    {
        $this->sendToUser($appointment->user_id, 'appointment_reminder',
            'تذكير بموعد',
            "لديك موعد: {$appointment->title} في {$appointment->appointment_date->format('Y-m-d H:i')}",
            ['appointment_id' => $appointment->id],
            ['color' => 'info', 'sound' => 'reminder']
        );
    }

    public function appointmentCreated($appointment)
    {
        $this->sendToUser($appointment->user_id, 'appointment_created',
            'موعد جديد',
            "تم إنشاء موعد جديد: {$appointment->title}",
            ['appointment_id' => $appointment->id]
        );
    }

    // إشعارات النظام
    public function systemBackup($status)
    {
        $this->sendToAdmins('system_backup',
            $status === 'success' ? 'تم النسخ الاحتياطي بنجاح' : 'فشل النسخ الاحتياطي',
            $status === 'success' ? 'تم إنشاء نسخة احتياطية من النظام بنجاح' : 'فشل في إنشاء النسخة الاحتياطية',
            ['status' => $status],
            ['color' => $status === 'success' ? 'success' : 'danger']
        );
    }

    public function systemUpdate($version)
    {
        $this->sendToAdmins('system_update',
            'تحديث النظام',
            "تم تحديث النظام إلى الإصدار {$version}",
            ['version' => $version],
            ['color' => 'info']
        );
    }

    // دوال مساعدة
    private function getIconByType($type)
    {
        $icons = [
            'sale_created' => 'fas fa-shopping-cart',
            'installment_overdue' => 'fas fa-exclamation-triangle',
            'installment_paid' => 'fas fa-check-circle',
            'low_stock' => 'fas fa-box',
            'out_of_stock' => 'fas fa-times-circle',
            'campaign_created' => 'fas fa-bullhorn',
            'campaign_ending' => 'fas fa-clock',
            'campaign_budget_low' => 'fas fa-dollar-sign',
            'car_import_created' => 'fas fa-car',
            'car_shipped' => 'fas fa-ship',
            'car_arrived' => 'fas fa-check',
            'security_alert' => 'fas fa-shield-alt',
            'journal_entry_created' => 'fas fa-calculator',
            'appointment_reminder' => 'fas fa-calendar-alt',
            'appointment_created' => 'fas fa-calendar-plus',
            'system_backup' => 'fas fa-database',
            'system_update' => 'fas fa-sync-alt',
        ];

        return $icons[$type] ?? 'fas fa-bell';
    }

    private function getColorByType($type)
    {
        $colors = [
            'sale_created' => 'success',
            'installment_overdue' => 'danger',
            'installment_paid' => 'success',
            'low_stock' => 'warning',
            'out_of_stock' => 'danger',
            'campaign_created' => 'primary',
            'campaign_ending' => 'warning',
            'campaign_budget_low' => 'warning',
            'car_import_created' => 'info',
            'car_shipped' => 'info',
            'car_arrived' => 'success',
            'security_alert' => 'danger',
            'journal_entry_created' => 'primary',
            'appointment_reminder' => 'info',
            'appointment_created' => 'primary',
            'system_backup' => 'success',
            'system_update' => 'info',
        ];

        return $colors[$type] ?? 'primary';
    }

    private function getSoundByType($type)
    {
        $sounds = [
            'installment_overdue' => 'reminder',
            'low_stock' => 'reminder',
            'out_of_stock' => 'reminder',
            'campaign_ending' => 'reminder',
            'security_alert' => 'delete',
            'appointment_reminder' => 'reminder',
        ];

        return $sounds[$type] ?? 'notification';
    }
}
