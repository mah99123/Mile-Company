<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Product;
use App\Models\Sale;
use App\Models\CarImport;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        // Statistics
        $campaignCount = Campaign::whereBetween('created_at', [$startDate, $endDate])->count();
        $productCount = Product::count();
        $saleCount = Sale::whereBetween('sale_date', [$startDate, $endDate])->count();
        $carImportCount = CarImport::whereBetween('created_at', [$startDate, $endDate])->count();

        // Sales by month
        $salesByMonth = $this->getSalesByMonth($startDate, $endDate);

        // Installment status
        $installmentStatus = $this->getInstallmentStatus();

        // Campaign performance
        $campaignPerformance = $this->getCampaignPerformance($startDate, $endDate);

        // Product inventory
        $productInventory = $this->getProductInventory();

        // Recent data
        $recentSales = Sale::with('product')->orderBy('sale_date', 'desc')->limit(5)->get();
        $recentCampaigns = Campaign::orderBy('start_date', 'desc')->limit(5)->get();
        $recentCarImports = CarImport::orderBy('created_at', 'desc')->limit(5)->get();

        return view('reports.index', compact(
            'campaignCount',
            'productCount', 
            'saleCount',
            'carImportCount',
            'salesByMonth',
            'installmentStatus',
            'campaignPerformance',
            'productInventory',
            'recentSales',
            'recentCampaigns',
            'recentCarImports'
        ));
    }

    public function campaigns(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $campaigns = Campaign::whereBetween('start_date', [$startDate, $endDate])
            ->paginate(15);

        // Use existing columns from the campaigns table
        $totalBudget = Campaign::whereBetween('start_date', [$startDate, $endDate])->sum('budget_total');
        
        // Check if new columns exist before using them
        $totalLeads = 0;
        $totalConversions = 0;
        
        if (Schema::hasColumn('campaigns', 'leads')) {
            $totalLeads = Campaign::whereBetween('start_date', [$startDate, $endDate])->sum('leads');
        }
        
        if (Schema::hasColumn('campaigns', 'conversions')) {
            $totalConversions = Campaign::whereBetween('start_date', [$startDate, $endDate])->sum('conversions');
        }

        return view('reports.campaigns', compact('campaigns', 'totalBudget', 'totalLeads', 'totalConversions'));
    }

    public function sales(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $sales = Sale::with(['product'])
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->paginate(15);

        $totalRevenue = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $totalSales = Sale::whereBetween('sale_date', [$startDate, $endDate])->count();
        $averageSale = $totalSales > 0 ? $totalRevenue / $totalSales : 0;

        return view('reports.sales', compact('sales', 'totalRevenue', 'totalSales', 'averageSale'));
    }

    public function inventory()
    {
        $products = Product::orderBy('quantity_in_stock', 'asc')->paginate(15);
        $lowStockProducts = Product::where('quantity_in_stock', '<', 10)->count();
        $outOfStockProducts = Product::where('quantity_in_stock', '=', 0)->count();
        $totalProducts = Product::count();

        return view('reports.inventory', compact('products', 'lowStockProducts', 'outOfStockProducts', 'totalProducts'));
    }

    public function carImports(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $carImports = CarImport::whereBetween('created_at', [$startDate, $endDate])
            ->paginate(15);

        $totalCost = CarImport::whereBetween('created_at', [$startDate, $endDate])->sum('total_with_transfer');
        $totalImports = CarImport::whereBetween('created_at', [$startDate, $endDate])->count();

        return view('reports.car-imports', compact('carImports', 'totalCost', 'totalImports'));
    }

    public function profitLoss(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonths(3)->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));

        $revenue = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $expenses = Campaign::whereBetween('start_date', [$startDate, $endDate])->sum('budget_total');
        $carImportCosts = CarImport::whereBetween('created_at', [$startDate, $endDate])->sum('total_with_transfer');
        
        $totalExpenses = $expenses + $carImportCosts;
        $profit = $revenue - $totalExpenses;

        return view('reports.profit-loss', compact('revenue', 'expenses', 'carImportCosts', 'totalExpenses', 'profit'));
    }

    public function exportPdf()
    {
        return response()->json(['message' => 'PDF export functionality will be implemented']);
    }

    public function exportExcel()
    {
        return response()->json(['message' => 'Excel export functionality will be implemented']);
    }

    private function getSalesByMonth($startDate, $endDate)
    {
        $sales = Sale::selectRaw('MONTH(sale_date) as month, YEAR(sale_date) as year, SUM(total_amount) as total')
            ->whereBetween('sale_date', [$startDate, $endDate])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $months = [];
        $totals = [];

        foreach ($sales as $sale) {
            $months[] = Carbon::createFromDate($sale->year, $sale->month, 1)->format('M Y');
            $totals[] = $sale->total;
        }

        return [
            'months' => $months,
            'totals' => $totals
        ];
    }

    private function getInstallmentStatus()
    {
        $paid = InstallmentPayment::where('status', 'paid')->count();
        $pending = InstallmentPayment::where('status', 'pending')->count();
        $overdue = InstallmentPayment::where('status', 'overdue')->count();

        return [
            'labels' => ['مدفوع', 'معلق', 'متأخر'],
            'data' => [$paid, $pending, $overdue]
        ];
    }

    private function getCampaignPerformance($startDate, $endDate)
    {
        // Use existing columns and provide fallbacks
        $campaigns = Campaign::whereBetween('start_date', [$startDate, $endDate])
            ->limit(10)
            ->get();

        $labels = [];
        $leads = [];
        $conversions = [];

        foreach ($campaigns as $campaign) {
            // Use page_name as the campaign name
            $labels[] = $campaign->page_name ?? 'Campaign';
            
            // Check if columns exist before accessing them
            $leads[] = Schema::hasColumn('campaigns', 'leads') ? ($campaign->leads ?? 0) : 0;
            $conversions[] = Schema::hasColumn('campaigns', 'conversions') ? ($campaign->conversions ?? 0) : 0;
        }

        return [
            'labels' => $labels,
            'leads' => $leads,
            'conversions' => $conversions
        ];
    }

    private function getProductInventory()
    {
        $products = Product::select('name', 'quantity_in_stock')
            ->orderBy('quantity_in_stock', 'desc')
            ->limit(10)
            ->get();

        $labels = [];
        $quantities = [];

        foreach ($products as $product) {
            $labels[] = $product->name;
            $quantities[] = $product->quantity_in_stock;
        }

        return [
            'labels' => $labels,
            'quantities' => $quantities
        ];
    }
}
