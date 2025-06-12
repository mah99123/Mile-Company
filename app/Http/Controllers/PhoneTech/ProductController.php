<?php

namespace App\Http\Controllers\PhoneTech;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('supplier');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('sku', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('low_stock')) {
            $query->whereRaw('quantity_in_stock <= reorder_threshold');
        }

        $products = $query->latest()->paginate(15);

        return view('phonetech.products.index', compact('products'));
    }

    public function create()
    {
        $suppliers = Supplier::where('status', 'active')->get();
        return view('phonetech.products.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Phone,Accessory,Electronics',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
            'reorder_threshold' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
        ]);

        Product::create($validated);

        return redirect()->route('phonetech.products.index')
            ->with('success', 'تم إضافة المنتج بنجاح');
    }

    public function show(Product $product)
    {
        $product->load('supplier');
        return view('phonetech.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $suppliers = Supplier::where('status', 'active')->get();
        return view('phonetech.products.edit', compact('product', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:Phone,Accessory,Electronics',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'quantity_in_stock' => 'required|integer|min:0',
            'reorder_threshold' => 'required|integer|min:0',
            'sku' => 'required|string|unique:products,sku,' . $product->product_id . ',product_id',
            'description' => 'nullable|string',
            'supplier_id' => 'required|exists:suppliers,supplier_id',
            'status' => 'required|in:active,inactive',
        ]);

        $product->update($validated);

        return redirect()->route('phonetech.products.show', $product)
            ->with('success', 'تم تحديث المنتج بنجاح');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('phonetech.products.index')
            ->with('success', 'تم حذف المنتج بنجاح');
    }

    public function updateStock(Request $request, Product $product)
    {
        $validated = $request->validate([
            'update_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $currentStock = $product->quantity_in_stock;

        switch ($validated['update_type']) {
            case 'add':
                $newStock = $currentStock + $validated['quantity'];
                break;
            case 'subtract':
                $newStock = max(0, $currentStock - $validated['quantity']);
                break;
            case 'set':
                $newStock = $validated['quantity'];
                break;
        }

        $product->update(['quantity_in_stock' => $newStock]);

        return redirect()->route('phonetech.products.show', $product)
            ->with('success', 'تم تحديث المخزون بنجاح');
    }
}
