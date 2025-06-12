<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\GeneralJournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountController extends Controller
{
    public function index()
    {
        $accounts = Account::orderBy('platform')
            ->orderBy('account_type')
            ->orderBy('account_name')
            ->get();
            
        // Group accounts by platform and type
        $groupedAccounts = $accounts->groupBy('platform')->map(function ($platformAccounts) {
            return $platformAccounts->groupBy('account_type');
        });
        
        // Group accounts by type for summary
        $accountsByType = $accounts->groupBy('account_type');
        
        // Group accounts by platform for summary
        $accountsByPlatform = $accounts->groupBy('platform');
        
        // Get account balances
        $accountBalances = $this->calculateAccountBalances();
        
        return view('admin.accounts.index', compact('groupedAccounts', 'accountsByType', 'accountsByPlatform', 'accountBalances'));
    }
    
    public function create()
    {
        return view('admin.accounts.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|in:Asset,Liability,Equity,Revenue,Expense',
            'platform' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        // Generate account ID
        $accountPrefix = substr($validated['account_type'], 0, 1);
        $lastAccount = Account::where('account_type', $validated['account_type'])
            ->orderBy('account_id', 'desc')
            ->first();
            
        if ($lastAccount) {
            $lastNumber = (int) substr($lastAccount->account_id, 1);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1000;
        }
        
        $accountId = $accountPrefix . $newNumber;
        
        Account::create([
            'account_id' => $accountId,
            'account_name' => $validated['account_name'],
            'account_type' => $validated['account_type'],
            'platform' => $validated['platform'],
            'description' => $validated['description'],
            'balance' => 0,
        ]);
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'تم إنشاء الحساب بنجاح');
    }
    
    public function show(Account $account)
    {
        // Get journal entries for this account
        $entries = GeneralJournalEntry::where('debit_account', $account->account_id)
            ->orWhere('credit_account', $account->account_id)
            ->orderBy('date', 'desc')
            ->paginate(15);
            
        // Calculate account balance
        $balance = $this->calculateSingleAccountBalance($account->account_id);
        
        return view('admin.accounts.show', compact('account', 'entries', 'balance'));
    }
    
    public function edit(Account $account)
    {
        return view('admin.accounts.edit', compact('account'));
    }
    
    public function update(Request $request, Account $account)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'platform' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        $account->update([
            'account_name' => $validated['account_name'],
            'platform' => $validated['platform'],
            'description' => $validated['description'],
        ]);
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'تم تحديث الحساب بنجاح');
    }
    
    public function destroy(Account $account)
    {
        // Check if account has journal entries
        $hasEntries = GeneralJournalEntry::where('debit_account', $account->account_id)
            ->orWhere('credit_account', $account->account_id)
            ->exists();
            
        if ($hasEntries) {
            return redirect()->route('admin.accounts.index')
                ->with('error', 'لا يمكن حذف الحساب لأنه يحتوي على قيود محاسبية');
        }
        
        $account->delete();
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'تم حذف الحساب بنجاح');
    }
    
    public function createJournalEntry()
    {
        $accounts = Account::orderBy('platform')
            ->orderBy('account_type')
            ->orderBy('account_name')
            ->get();
            
        return view('admin.accounts.create-journal-entry', compact('accounts'));
    }
    
    public function storeJournalEntry(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'debit_account' => 'required|exists:accounts,account_id',
            'credit_account' => 'required|exists:accounts,account_id|different:debit_account',
            'amount' => 'required|numeric|min:0.01',
            'reference_number' => 'nullable|string|max:50',
        ]);
        
        GeneralJournalEntry::create($validated);
        
        return redirect()->route('admin.accounts.index')
            ->with('success', 'تم إنشاء القيد المحاسبي بنجاح');
    }
    
    public function journalEntries()
    {
        $entries = GeneralJournalEntry::with(['debitAccount', 'creditAccount'])
            ->orderBy('date', 'desc')
            ->paginate(20);
            
        return view('admin.accounts.journal-entries', compact('entries'));
    }
    
    public function ledger(Request $request)
    {
        $query = GeneralJournalEntry::query();
        
        if ($request->filled('start_date')) {
            $query->where('date', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->where('date', '<=', $request->end_date);
        }
        
        if ($request->filled('account_id')) {
            $query->where(function($q) use ($request) {
                $q->where('debit_account', $request->account_id)
                  ->orWhere('credit_account', $request->account_id);
            });
        }
        
        $entries = $query->orderBy('date', 'desc')
            ->paginate(20);
            
        $accounts = Account::orderBy('platform')
            ->orderBy('account_type')
            ->orderBy('account_name')
            ->get();
            
        return view('admin.accounts.ledger', compact('entries', 'accounts'));
    }
    
    public function trialBalance()
    {
        $accounts = Account::orderBy('account_type')
            ->orderBy('account_id')
            ->get();
            
        $balances = $this->calculateAccountBalances();
        
        // Group accounts by type
        $groupedAccounts = $accounts->groupBy('account_type');
        
        // Calculate totals
        $totalDebits = 0;
        $totalCredits = 0;
        
        foreach ($balances as $accountId => $balance) {
            $account = $accounts->firstWhere('account_id', $accountId);
            
            if (in_array($account->account_type, ['Asset', 'Expense'])) {
                if ($balance > 0) {
                    $totalDebits += $balance;
                } else {
                    $totalCredits += abs($balance);
                }
            } else {
                if ($balance > 0) {
                    $totalCredits += $balance;
                } else {
                    $totalDebits += abs($balance);
                }
            }
        }
        
        return view('admin.accounts.trial-balance', compact('groupedAccounts', 'balances', 'totalDebits', 'totalCredits'));
    }
    
    public function balanceSheet()
    {
        $accounts = Account::whereIn('account_type', ['Asset', 'Liability', 'Equity'])
            ->orderBy('account_type')
            ->orderBy('account_id')
            ->get();
            
        $balances = $this->calculateAccountBalances();
        
        // Group accounts by type
        $groupedAccounts = $accounts->groupBy('account_type');
        
        // Calculate totals
        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquity = 0;
        
        foreach ($balances as $accountId => $balance) {
            $account = $accounts->firstWhere('account_id', $accountId);
            
            if (!$account) continue;
            
            if ($account->account_type == 'Asset') {
                $totalAssets += $balance;
            } elseif ($account->account_type == 'Liability') {
                $totalLiabilities += $balance;
            } elseif ($account->account_type == 'Equity') {
                $totalEquity += $balance;
            }
        }
        
        return view('admin.accounts.balance-sheet', compact('groupedAccounts', 'balances', 'totalAssets', 'totalLiabilities', 'totalEquity'));
    }
    
    public function incomeStatement(Request $request)
    {
        $startDate = $request->filled('start_date') ? $request->start_date : Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = $request->filled('end_date') ? $request->end_date : Carbon::now()->format('Y-m-d');
        
        $accounts = Account::whereIn('account_type', ['Revenue', 'Expense'])
            ->orderBy('account_type')
            ->orderBy('account_id')
            ->get();
            
        $balances = $this->calculateAccountBalances($startDate, $endDate);
        
        // Group accounts by type
        $groupedAccounts = $accounts->groupBy('account_type');
        
        // Calculate totals
        $totalRevenue = 0;
        $totalExpenses = 0;
        
        foreach ($balances as $accountId => $balance) {
            $account = $accounts->firstWhere('account_id', $accountId);
            
            if (!$account) continue;
            
            if ($account->account_type == 'Revenue') {
                $totalRevenue += $balance;
            } elseif ($account->account_type == 'Expense') {
                $totalExpenses += $balance;
            }
        }
        
        $netIncome = $totalRevenue - $totalExpenses;
        
        return view('admin.accounts.income-statement', compact(
            'groupedAccounts', 
            'balances', 
            'totalRevenue', 
            'totalExpenses', 
            'netIncome',
            'startDate',
            'endDate'
        ));
    }
    
    public function export()
    {
        // Export functionality
        return response()->json(['message' => 'Export functionality coming soon']);
    }
    
    private function calculateAccountBalances($startDate = null, $endDate = null)
    {
        $query = GeneralJournalEntry::query();
        
        if ($startDate) {
            $query->where('date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('date', '<=', $endDate);
        }
        
        $entries = $query->get();
        
        $balances = [];
        
        foreach ($entries as $entry) {
            // Debit account
            if (!isset($balances[$entry->debit_account])) {
                $balances[$entry->debit_account] = 0;
            }
            $balances[$entry->debit_account] += $entry->amount;
            
            // Credit account
            if (!isset($balances[$entry->credit_account])) {
                $balances[$entry->credit_account] = 0;
            }
            $balances[$entry->credit_account] -= $entry->amount;
        }
        
        return $balances;
    }
    
    private function calculateSingleAccountBalance($accountId)
    {
        $debitTotal = GeneralJournalEntry::where('debit_account', $accountId)->sum('amount');
        $creditTotal = GeneralJournalEntry::where('credit_account', $accountId)->sum('amount');
        
        return $debitTotal - $creditTotal;
    }
}
