<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Campaign;
use App\Models\CampaignUpdate;
use App\Models\WhatsappThread;
use Carbon\Carbon;

class CampaignSeeder extends Seeder
{
    public function run(): void
    {
        $campaigns = [
            [
                'page_name' => 'عيادة الدكتور أحمد للأسنان',
                'owner_name' => 'د. أحمد الشمري',
                'specialization' => 'طب الأسنان',
                'budget_total' => 15000.00,
                'start_date' => Carbon::now()->subDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'launch_date' => Carbon::now()->subDays(28),
                'stop_date' => null,
                'notes' => 'التركيز على زراعة الأسنان والتقويم',
                'created_by' => 2,
                'status' => 'active',
            ],
            [
                'page_name' => 'مطعم الشرق للمأكولات البحرية',
                'owner_name' => 'محمد العتيبي',
                'specialization' => 'مطاعم',
                'budget_total' => 8000.00,
                'start_date' => Carbon::now()->subDays(15),
                'end_date' => Carbon::now()->addDays(15),
                'launch_date' => Carbon::now()->subDays(14),
                'stop_date' => null,
                'notes' => 'الترويج للوجبات الجديدة والعروض الخاصة',
                'created_by' => 2,
                'status' => 'active',
            ],
            [
                'page_name' => 'صالون لمسة جمال',
                'owner_name' => 'سارة الدوسري',
                'specialization' => 'تجميل',
                'budget_total' => 12000.00,
                'start_date' => Carbon::now()->subDays(45),
                'end_date' => Carbon::now()->subDays(15),
                'launch_date' => Carbon::now()->subDays(44),
                'stop_date' => Carbon::now()->subDays(15),
                'notes' => 'الترويج لخدمات التجميل والعناية بالبشرة',
                'created_by' => 2,
                'status' => 'completed',
            ],
            [
                'page_name' => 'مركز الخليج للتدريب',
                'owner_name' => 'فهد السالم',
                'specialization' => 'تعليم',
                'budget_total' => 20000.00,
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(65),
                'launch_date' => Carbon::now()->addDays(5),
                'stop_date' => null,
                'notes' => 'الترويج للدورات الجديدة في مجال التسويق الرقمي',
                'created_by' => 2,
                'status' => 'paused',
            ],
            [
                'page_name' => 'عقارات الرياض الحديثة',
                'owner_name' => 'خالد المطيري',
                'specialization' => 'عقارات',
                'budget_total' => 30000.00,
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(80),
                'launch_date' => Carbon::now()->subDays(9),
                'stop_date' => null,
                'notes' => 'الترويج للمشاريع السكنية الجديدة في شمال الرياض',
                'created_by' => 2,
                'status' => 'active',
            ],
        ];

        foreach ($campaigns as $campaign) {
            $createdCampaign = Campaign::create($campaign);
            
            // Add campaign updates
            for ($i = 1; $i <= 3; $i++) {
                CampaignUpdate::create([
                    'campaign_id' => $createdCampaign->campaign_id,
                    'update_text' => "تحديث رقم {$i} للحملة: " . fake()->paragraph(),
                    'update_date' => Carbon::now()->subDays(rand(1, 20)),
                ]);
            }
            
            // Add WhatsApp threads
            if ($createdCampaign->status === 'active') {
                for ($i = 1; $i <= 5; $i++) {
                    WhatsappThread::create([
                        'campaign_id' => $createdCampaign->campaign_id,
                        'customer_whatsapp' => '+9645' . rand(10000000, 99999999),
                        'message_content' => fake()->sentence(),
                        'message_date' => Carbon::now()->subDays(rand(1, 10)),
                        'message_type' => rand(0, 1) ? 'sent' : 'received',
                    ]);
                }
            }
        }
    }
}
