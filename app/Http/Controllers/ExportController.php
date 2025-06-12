<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\CarImport;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ExportController extends Controller
{
    public function index()
    {
        return view('exports.index');
    }

    public function salesExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Sale::with('product');
        
        if ($startDate) {
            $query->whereDate('sale_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('sale_date', '<=', $endDate);
        }
        
        $sales = $query->get();
        
        // For now, return JSON. In production, you'd use Laravel Excel
        return response()->json([
            'message' => 'Sales Excel export',
            'data' => $sales,
            'count' => $sales->count()
        ]);
    }

    public function salesPdf(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Sale::with('product');
        
        if ($startDate) {
            $query->whereDate('sale_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('sale_date', '<=', $endDate);
        }
        
        $sales = $query->get();
        
        return view('exports.sales_pdf', compact('sales', 'startDate', 'endDate'));
    }

    public function productsExcel()
    {
        $products = Product::with('supplier')->get();
        
        return response()->json([
            'message' => 'Products Excel export',
            'data' => $products,
            'count' => $products->count()
        ]);
    }

    public function campaignsExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = Campaign::query();
        
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('end_date', '<=', $endDate);
        }
        
        $campaigns = $query->get();
        
        return response()->json([
            'message' => 'Campaigns Excel export',
            'data' => $campaigns,
            'count' => $campaigns->count()
        ]);
    }

    public function carImportsExcel(Request $request)
    {
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        
        $query = CarImport::query();
        
        if ($startDate) {
            $query->whereDate('auction_invoice_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->whereDate('auction_invoice_date', '<=', $endDate);
        }
        
        $carImports = $query->get();
        
        return response()->json([
            'message' => 'Car Imports Excel export',
            'data' => $carImports,
            'count' => $carImports->count()
        ]);
    }

    public function installmentsExcel(Request $request)
    {
        $status = $request->get('status');
        
        $query = InstallmentPayment::with('sale');
        
        if ($status) {
            $query->where('status', $status);
        }
        
        $installments = $query->get();
        
        return response()->json([
            'message' => 'Installments Excel export',
            'data' => $installments,
            'count' => $installments->count()
        ]);
    }

    public function profitLossPdf(Request $request)
    {
        $year = $request->get('year', date('Y'));
        $month = $request->get('month');
        
        $startDate = $month ? "$year-$month-01" : "$year-01-01";
        $endDate = $month ? date('Y-m-t', strtotime($startDate)) : "$year-12-31";
        
        $revenue = Sale::whereBetween('sale_date', [$startDate, $endDate])->sum('total_amount');
        $expenses = Campaign::whereBetween('start_date', [$startDate, $endDate])->sum('budget_total');
        $carImportCosts = CarImport::whereBetween('created_at', [$startDate, $endDate])->sum('total_with_transfer');
        
        $totalExpenses = $expenses + $carImportCosts;
        $profit = $revenue - $totalExpenses;
        
        return view('exports.profit_loss_pdf', compact(
            'revenue', 
            'expenses', 
            'carImportCosts', 
            'totalExpenses', 
            'profit', 
            'year', 
            'month',
            'startDate',
            'endDate'
        ));
    }
}
