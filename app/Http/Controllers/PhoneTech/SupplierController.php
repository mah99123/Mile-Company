<?php

namespace App\Http\Controllers\PhoneTech;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $suppliers = $query->latest()->paginate(15);

        return view('phonetech.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('phonetech.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
        ]);

        Supplier::create($validated);

        return redirect()->route('phonetech.suppliers.index')
            ->with('success', 'تم إضافة المورد بنجاح');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('products');
        return view('phonetech.suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        return view('phonetech.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $supplier->update($validated);

        return redirect()->route('phonetech.suppliers.show', $supplier)
            ->with('success', 'تم تحديث المورد بنجاح');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return back()->with('error', 'لا يمكن حذف المورد لأنه مرتبط بمنتجات');
        }

        $supplier->delete();

        return redirect()->route('phonetech.suppliers.index')
            ->with('success', 'تم حذف المورد بنجاح');
    }
}
