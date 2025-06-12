<?php

namespace App\Http\Controllers\PhoneTech;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Product;
use App\Models\InstallmentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['product', 'creator']);

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('overdue')) {
            $query->where('status', 'Active')
                  ->where('next_installment_due_date', '<', now());
        }

        $sales = $query->latest()->paginate(15);

        return view('phonetech.sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::where('status', 'active')
                          ->where('quantity_in_stock', '>', 0)
                          ->get();
        return view('phonetech.sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'product_id' => 'required|exists:products,product_id',
            'sale_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'down_payment' => 'required|numeric|min:0',
            'installment_period_months' => 'required|integer|min:1|max:60',
        ]);

        DB::transaction(function () use ($validated) {
            $remainingAmount = $validated['total_amount'] - $validated['down_payment'];
            $installmentAmount = $remainingAmount / $validated['installment_period_months'];

            $sale = Sale::create([
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'product_id' => $validated['product_id'],
                'sale_date' => $validated['sale_date'],
                'total_amount' => $validated['total_amount'],
                'down_payment' => $validated['down_payment'],
                'remaining_amount' => $remainingAmount,
                'installment_amount' => $installmentAmount,
                'installment_period_months' => $validated['installment_period_months'],
                'next_installment_due_date' => now()->addMonth(),
                'status' => $remainingAmount > 0 ? 'Active' : 'Completed',
                'created_by' => Auth::id(),
            ]);

            // Update product stock
            $product = Product::find($validated['product_id']);
            $product->decrement('quantity_in_stock');

            // Record down payment if any
            if ($validated['down_payment'] > 0) {
                InstallmentPayment::create([
                    'invoice_id' => $sale->invoice_id,
                    'payment_date' => $validated['sale_date'],
                    'amount_paid' => $validated['down_payment'],
                    'receipt_number' => 'DOWN-' . $sale->invoice_id,
                    'notes' => 'دفعة مقدمة',
                ]);
            }
        });

        return redirect()->route('phonetech.sales.index')
            ->with('success', 'تم إنشاء الفاتورة بنجاح');
    }

    public function show(Sale $sale)
    {
        $sale->load(['product', 'creator', 'installmentPayments']);
        return view('phonetech.sales.show', compact('sale'));
    }

    public function addPayment(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'amount_paid' => 'required|numeric|min:0|max:' . $sale->remaining_amount,
            'payment_date' => 'required|date',
            'receipt_number' => 'required|string|unique:installment_payments,receipt_number',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $sale) {
            // Create payment record
            InstallmentPayment::create([
                'invoice_id' => $sale->invoice_id,
                'payment_date' => $validated['payment_date'],
                'amount_paid' => $validated['amount_paid'],
                'receipt_number' => $validated['receipt_number'],
                'notes' => $validated['notes'],
            ]);

            // Update sale record
            $sale->increment('installments_paid');
            $sale->decrement('remaining_amount', $validated['amount_paid']);

            // Update next due date and status
            if ($sale->remaining_amount <= 0) {
                $sale->update(['status' => 'Completed']);
            } else {
                $sale->update([
                    'next_installment_due_date' => now()->addMonth()
                ]);
            }
        });

        return back()->with('success', 'تم تسجيل الدفعة بنجاح');
    }
}
