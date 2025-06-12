<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\CarImport;
use App\Models\InstallmentPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * عرض لوحة التحكم الرئيسية
     */
    public function index()
    {
        // إحصائيات عامة
        $totalSales = Sale::count();
        $totalProducts = Product::count();
        $totalCampaigns = Campaign::count();
        $totalCarImports = CarImport::count();
        $totalUsers = User::count();
        
        // إحصائيات المبيعات
        $salesData = $this->getSalesChartData();
        
        // إحصائيات الأقساط
        $installmentStats = $this->getInstallmentStats();
        
        // إحصائيات الحملات
        $campaignStats = $this->getCampaignStats();
        
        // إحصائيات استيراد السيارات
        $carImportStats = $this->getCarImportStats();
        
        // أحدث المبيعات
        $latestSales = Sale::with(['product', 'creator'])
            ->latest()
            ->take(5)
            ->get();
        
        // أحدث الحملات
        $latestCampaigns = Campaign::latest()
            ->take(5)
            ->get();
        
        // أحدث عمليات استيراد السيارات
        $latestCarImports = CarImport::latest()
            ->take(5)
            ->get();
        
        // الأقساط المتأخرة
        $overdueInstallments = InstallmentPayment::where('due_date', '<', now())
            ->where('status', 'pending')
            ->count();
        
        return view('dashboard', compact(
            'totalSales',
            'totalProducts',
            'totalCampaigns',
            'totalCarImports',
            'totalUsers',
            'salesData',
            'installmentStats',
            'campaignStats',
            'carImportStats',
            'latestSales',
            'latestCampaigns',
            'latestCarImports',
            'overdueInstallments'
        ));
    }

    /**
     * الحصول على بيانات الرسم البياني للمبيعات
     */
    private function getSalesChartData()
    {
        $startDate = Carbon::now()->subMonths(6)->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();
        
        $salesData = Sale::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        $formattedData = [];
        $currentDate = $startDate->copy();
        
        while ($currentDate <= $endDate) {
            $year = $currentDate->year;
            $month = $currentDate->month;
            
            $found = false;
            foreach ($salesData as $data) {
                if ($data->year == $year && $data->month == $month) {
                    $formattedData[] = [
                        'month' => $currentDate->format('M Y'),
                        'total' => $data->total
                    ];
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $formattedData[] = [
                    'month' => $currentDate->format('M Y'),
                    'total' => 0
                ];
            }
            
            $currentDate->addMonth();
        }
        
        return $formattedData;
    }

    /**
     * الحصول على إحصائيات الأقساط
     */
    private function getInstallmentStats()
    {
        $paid = InstallmentPayment::where('status', 'paid')->count();
        $pending = InstallmentPayment::where('status', 'pending')
            ->where('due_date', '>=', now())
            ->count();
        $overdue = InstallmentPayment::where('status', 'pending')
            ->where('due_date', '<', now())
            ->count();
        
        return [
            'paid' => $paid,
            'pending' => $pending,
            'overdue' => $overdue
        ];
    }

    /**
     * الحصول على إحصائيات الحملات
     */
    private function getCampaignStats()
    {
        $active = Campaign::where('status', 'active')->count();
        $completed = Campaign::where('status', 'completed')->count();
        $pending = Campaign::where('status', 'pending')->count();
        
        return [
            'active' => $active,
            'completed' => $completed,
            'pending' => $pending
        ];
    }

    /**
     * الحصول على إحصائيات استيراد السيارات
     */
    private function getCarImportStats()
    {
        $pending = CarImport::where('shipping_status', 'Pending')->count();
        $shipped = CarImport::where('shipping_status', 'Shipped')->count();
        $arrived = CarImport::where('shipping_status', 'Arrived')->count();
        $delivered = CarImport::where('shipping_status', 'Delivered')->count();
        
        return [
            'pending' => $pending,
            'shipped' => $shipped,
            'arrived' => $arrived,
            'delivered' => $delivered
        ];
    }
}
