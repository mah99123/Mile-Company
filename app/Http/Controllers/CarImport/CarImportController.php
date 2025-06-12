<?php

namespace App\Http\Controllers\CarImport;

use App\Http\Controllers\Controller;
use App\Models\CarImport;
use App\Models\User;
use Illuminate\Http\Request;

class CarImportController extends Controller
{
    public function index(Request $request)
    {
        $query = CarImport::with('employee');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('lot_number', 'like', '%' . $request->search . '%')
                  ->orWhere('buyer_company', 'like', '%' . $request->search . '%')
                  ->orWhere('shipping_company', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('shipping_status')) {
            $query->where('shipping_status', $request->shipping_status);
        }

        if ($request->filled('buyer_company')) {
            $query->where('buyer_company', $request->buyer_company);
        }

        $carImports = $query->latest()->paginate(15);
        $buyerCompanies = CarImport::distinct()->pluck('buyer_company');

        return view('carimport.imports.index', compact('carImports', 'buyerCompanies'));
    }

    public function create()
    {
        $employees = User::where('status', 'active')->get();
        return view('carimport.imports.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'auction_type' => 'required|string|max:255',
            'lot_number' => 'required|string|max:255',
            'total_with_transfer' => 'required|numeric|min:0',
            'amount_received' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'auction_invoice_date' => 'required|date',
            'auction_invoice_number' => 'required|string|max:255',
            'office_contract_number' => 'required|string|max:255',
            'office_contract_date' => 'required|date',
            'office_invoice_amount' => 'required|numeric|min:0',
            'company_shipping_cost' => 'required|numeric|min:0',
            'customer_shipping_cost' => 'required|numeric|min:0',
            'remaining_office_invoice' => 'required|numeric|min:0',
            'shipping_profit' => 'required|numeric',
            'office_commission' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'employee_assigned' => 'required|exists:users,id',
            'shipping_company' => 'required|string|max:255',
            'buyer_company' => 'required|string|max:255',
            'pull_date' => 'nullable|date',
            'shipping_date' => 'nullable|date',
            'arrival_date' => 'nullable|date',
            'container_number' => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_receive_date' => 'nullable|date',
            'recipient_phone' => 'nullable|string|max:20',
        ]);

        CarImport::create($validated);

        return redirect()->route('carimport.imports.index')
            ->with('success', 'تم إضافة عملية الاستيراد بنجاح');
    }

    public function show(CarImport $carImport)
    {
        $carImport->load('employee');
        return view('carimport.imports.show', compact('carImport'));
    }

    public function edit(CarImport $carImport)
    {
        $employees = User::where('status', 'active')->get();
        return view('carimport.imports.edit', compact('carImport', 'employees'));
    }

    public function update(Request $request, CarImport $carImport)
    {
        $validated = $request->validate([
            'auction_type' => 'required|string|max:255',
            'lot_number' => 'required|string|max:255',
            'total_with_transfer' => 'required|numeric|min:0',
            'amount_received' => 'required|numeric|min:0',
            'remaining_amount' => 'required|numeric|min:0',
            'auction_invoice_date' => 'required|date',
            'auction_invoice_number' => 'required|string|max:255',
            'office_contract_number' => 'required|string|max:255',
            'office_contract_date' => 'required|date',
            'office_invoice_amount' => 'required|numeric|min:0',
            'company_shipping_cost' => 'required|numeric|min:0',
            'customer_shipping_cost' => 'required|numeric|min:0',
            'remaining_office_invoice' => 'required|numeric|min:0',
            'shipping_profit' => 'required|numeric',
            'office_commission' => 'required|numeric',
            'currency' => 'required|string|max:10',
            'notes' => 'nullable|string',
            'employee_assigned' => 'required|exists:users,id',
            'shipping_status' => 'required|in:Pending,Shipped,Arrived,Delivered',
            'shipping_company' => 'required|string|max:255',
            'buyer_company' => 'required|string|max:255',
            'pull_date' => 'nullable|date',
            'shipping_date' => 'nullable|date',
            'arrival_date' => 'nullable|date',
            'container_number' => 'nullable|string|max:255',
            'recipient_name' => 'nullable|string|max:255',
            'recipient_receive_date' => 'nullable|date',
            'recipient_phone' => 'nullable|string|max:20',
        ]);

        $carImport->update($validated);

        return redirect()->route('carimport.imports.show', $carImport)
            ->with('success', 'تم تحديث عملية الاستيراد بنجاح');
    }

    public function destroy(CarImport $carImport)
    {
        $carImport->delete();

        return redirect()->route('carimport.imports.index')
            ->with('success', 'تم حذف عملية الاستيراد بنجاح');
    }
}
