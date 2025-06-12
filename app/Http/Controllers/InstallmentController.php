<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\InstallmentPayment;
use App\Models\Account;
use App\Models\GeneralJournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['product', 'installmentPayments'])
            ->where('payment_type', 'installment')
            ->where('status', '!=', 'Completed');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_id', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_name', 'like', '%' . $request->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('overdue')) {
            $query->where('is_overdue', true);
        }

        $sales = $query->latest()->paginate(15);

        return view('phonetech.installments.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        if ($sale->payment_type != 'installment') {
            return redirect()->route('phonetech.sales.show', $sale)
                ->with('error', 'هذه الفاتورة ليست بنظام التقسيط');
        }

        $sale->load(['product', 'installmentPayments']);
        
        // Calculate next payment date and amount
        $nextPaymentDate = null;
        $nextPaymentAmount = 0;
        
        if ($sale->status != 'Completed') {
            $lastPayment = $sale->installmentPayments->sortByDesc('payment_date')->first();
            
            if ($lastPayment) {
                $nextPaymentDate = Carbon::parse($lastPayment->payment_date)->addMonth();
            } else {
                $nextPaymentDate = Carbon::parse($sale->sale_date)->addMonth();
            }
            
            $nextPaymentAmount = $sale->monthly_installment;
        }

        return view('phonetech.installments.show', compact('sale', 'nextPaymentDate', 'nextPaymentAmount'));
    }

    public function addPayment(Request $request, Sale $sale)
    {
        $validated = $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,bank_transfer,check',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated, $sale) {
            // Create installment payment
            $payment = InstallmentPayment::create([
                'sale_id' => $sale->id,
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
            ]);

            // Update sale remaining amount
            $sale->remaining_amount -= $validated['amount'];
            $sale->installments_paid += 1;
            
            // Check if fully paid
            if ($sale->remaining_amount <= 0) {
                $sale->status = 'Completed';
                $sale->remaining_amount = 0;
            } else {
                $sale->status = 'Active';
                $sale->is_overdue = false;
            }
            
            $sale->save();

            // Create accounting entry
            $cashAccount = Account::where('platform', 'PhoneTech')
                ->where('account_name', 'Cash')
                ->first();
                
            $installmentReceivableAccount = Account::where('platform', 'PhoneTech')
                ->where('account_name', 'Installment Receivables')
                ->first();

            if ($cashAccount && $installmentReceivableAccount) {
                GeneralJournalEntry::create([
                    'date' => $validated['payment_date'],
                    'description' => 'دفعة تقسيط للفاتورة رقم ' . $sale->invoice_id,
                    'debit_account' => $cashAccount->account_id,
                    'credit_account' => $installmentReceivableAccount->account_id,
                    'amount' => $validated['amount'],
                    'reference_number' => 'INS-' . $payment->id,
                ]);
            }
        });

        return redirect()->route('phonetech.installments.show', $sale)
            ->with('success', 'تم إضافة الدفعة بنجاح');
    }

    public function schedule(Sale $sale)
    {
        if ($sale->payment_type != 'installment') {
            return redirect()->route('phonetech.sales.show', $sale)
                ->with('error', 'هذه الفاتورة ليست بنظام التقسيط');
        }

        $sale->load('installmentPayments');
        
        // Generate payment schedule
        $schedule = [];
        $totalMonths = $sale->installment_period_months;
        $monthlyAmount = $sale->monthly_installment;
        $startDate = Carbon::parse($sale->sale_date);
        $paidInstallments = $sale->installmentPayments->sortBy('payment_date');
        
        for ($i = 0; $i < $totalMonths; $i++) {
            $dueDate = $startDate->copy()->addMonths($i + 1);
            $payment = $paidInstallments->where('payment_date', '<=', $dueDate)
                                       ->where('payment_date', '>', $startDate->copy()->addMonths($i))
                                       ->first();
            
            $schedule[] = [
                'installment_number' => $i + 1,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $monthlyAmount,
                'status' => $payment ? 'Paid' : ($dueDate->isPast() ? 'Overdue' : 'Pending'),
                'payment_date' => $payment ? $payment->payment_date : null,
                'payment_amount' => $payment ? $payment->amount : null,
            ];
        }

        return view('phonetech.installments.schedule', compact('sale', 'schedule'));
    }

    public function overdue()
    {
        $overdueInstallments = Sale::with(['product', 'installmentPayments'])
            ->where('payment_type', 'installment')
            ->where('status', '!=', 'Completed')
            ->where('is_overdue', true)
            ->latest()
            ->paginate(15);

        return view('phonetech.installments.overdue', compact('overdueInstallments'));
    }

    public function sendReminder(Sale $sale)
    {
        // Logic to send SMS or WhatsApp reminder
        // This is a placeholder for actual implementation
        
        return redirect()->route('phonetech.installments.show', $sale)
            ->with('success', 'تم إرسال التذكير بنجاح');
    }

    public function printSchedule(Sale $sale)
    {
        $sale->load('installmentPayments');
        
        // Generate payment schedule
        $schedule = [];
        $totalMonths = $sale->installment_period_months;
        $monthlyAmount = $sale->monthly_installment;
        $startDate = Carbon::parse($sale->sale_date);
        $paidInstallments = $sale->installmentPayments->sortBy('payment_date');
        
        for ($i = 0; $i < $totalMonths; $i++) {
            $dueDate = $startDate->copy()->addMonths($i + 1);
            $payment = $paidInstallments->where('payment_date', '<=', $dueDate)
                                       ->where('payment_date', '>', $startDate->copy()->addMonths($i))
                                       ->first();
            
            $schedule[] = [
                'installment_number' => $i + 1,
                'due_date' => $dueDate->format('Y-m-d'),
                'amount' => $monthlyAmount,
                'status' => $payment ? 'Paid' : ($dueDate->isPast() ? 'Overdue' : 'Pending'),
                'payment_date' => $payment ? $payment->payment_date : null,
                'payment_amount' => $payment ? $payment->amount : null,
            ];
        }

        return view('phonetech.installments.print-schedule', compact('sale', 'schedule'));
    }
}
