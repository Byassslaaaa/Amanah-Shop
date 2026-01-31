<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%');
            });
        }

        $suppliers = $query->orderBy('name')
            ->paginate(12)
            ->appends($request->query());

        return view('admin.inventory.suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('admin.inventory.suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        // Generate code
        $lastSupplier = Supplier::withTrashed()->orderBy('id', 'desc')->first();
        $number = $lastSupplier ? ((int) substr($lastSupplier->code, 3)) + 1 : 1;
        $code = 'SUP' . str_pad($number, 3, '0', STR_PAD_LEFT);

        Supplier::create([
            'code' => $code,
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.inventory.suppliers.index')
            ->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load('inventoryMovements.product');
        $totalPurchases = $supplier->getTotalPurchases();
        $lastPurchase = $supplier->inventoryMovements()->latest()->first();

        return view('admin.inventory.suppliers.show', compact('supplier', 'totalPurchases', 'lastPurchase'));
    }

    public function edit(Supplier $supplier)
    {
        return view('admin.inventory.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $supplier->update([
            'name' => $request->name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'notes' => $request->notes,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.inventory.suppliers.index')
            ->with('success', 'Supplier berhasil diupdate.');
    }

    public function destroy(Supplier $supplier)
    {
        // Check if supplier has inventory movements
        if ($supplier->inventoryMovements()->count() > 0) {
            return redirect()->route('admin.inventory.suppliers.index')
                ->with('error', 'Supplier tidak dapat dihapus karena masih memiliki riwayat transaksi.');
        }

        $supplier->delete();

        return redirect()->route('admin.inventory.suppliers.index')
            ->with('success', 'Supplier berhasil dihapus.');
    }

    public function toggleStatus(Supplier $supplier)
    {
        $supplier->update(['is_active' => !$supplier->is_active]);

        $status = $supplier->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Supplier berhasil {$status}.");
    }
}
