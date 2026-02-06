<?php

namespace App\Http\Controllers\Admin\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\InventoryMovement;
use App\Models\Product\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class InventoryMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryMovement::with(['product', 'supplier', 'creator']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        // Filter by supplier
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $movements = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();

        return view('admin.inventory.movements.index', compact('movements', 'products', 'suppliers'));
    }

    public function stockInForm()
    {
        $products = Product::orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        $categories = \App\Models\Product\Category::orderBy('name')->get();

        return view('admin.inventory.movements.stock-in', compact('products', 'suppliers', 'categories'));
    }

    public function storeStockIn(Request $request)
    {
        // Scenario 1: Bookkeeping only (no web display)
        if ($request->display_on_web === 'no') {
            $request->validate([
                'item_name' => 'required|string|max:255',
                'supplier_id' => 'required|exists:suppliers,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'document_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string'
            ]);

            InventoryMovement::record(
                null, // No product_id for bookkeeping-only
                'in',
                $request->quantity,
                null,
                $request->notes,
                [
                    'item_name' => $request->item_name,
                    'supplier_id' => $request->supplier_id,
                    'document_number' => $request->document_number,
                    'unit_price' => $request->unit_price,
                    'total_price' => $request->unit_price * $request->quantity,
                ]
            );

            return redirect()->route('admin.inventory.movements.index')
                ->with('success', 'Barang masuk berhasil dicatat (pencatatan saja).');
        }

        // Scenario 2 & 3: Display on web (new product or existing product)
        if ($request->product_selection === 'new') {
            // Scenario 2: Create new product for web
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price' => 'required|numeric|min:0',
                'category_id' => 'required|exists:categories,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'document_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string'
            ]);

            // Create product with initial stock 0
            $product = Product::create([
                'name' => $request->name,
                'slug' => \Illuminate\Support\Str::slug($request->name),
                'description' => $request->description,
                'price' => $request->price,
                'stock' => 0,
                'category_id' => $request->category_id,
                'status' => 'active',
            ]);

            // Record movement (this will update stock)
            InventoryMovement::record(
                $product->id,
                'in',
                $request->quantity,
                null,
                $request->notes,
                [
                    'supplier_id' => $request->supplier_id,
                    'document_number' => $request->document_number,
                    'unit_price' => $request->unit_price,
                    'total_price' => $request->unit_price * $request->quantity,
                ]
            );

            // Update product stock
            $product->increment('stock', $request->quantity);

            return redirect()->route('admin.inventory.movements.index')
                ->with('success', 'Produk baru berhasil dibuat dan stok masuk dicatat.');

        } else {
            // Scenario 3: Existing product
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'quantity' => 'required|integer|min:1',
                'unit_price' => 'required|numeric|min:0',
                'document_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string'
            ]);

            $product = Product::findOrFail($request->product_id);

            InventoryMovement::record(
                $product->id,
                'in',
                $request->quantity,
                null,
                $request->notes,
                [
                    'supplier_id' => $request->supplier_id,
                    'document_number' => $request->document_number,
                    'unit_price' => $request->unit_price,
                    'total_price' => $request->unit_price * $request->quantity,
                ]
            );

            // Update product stock
            $product->increment('stock', $request->quantity);

            return redirect()->route('admin.inventory.movements.index')
                ->with('success', 'Stok masuk berhasil dicatat.');
        }
    }

    public function stockOutForm()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.inventory.movements.stock-out', compact('products'));
    }

    public function storeStockOut(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'reference_type' => 'nullable|string|max:100',
            'notes' => 'required|string'
        ]);

        $product = Product::findOrFail($request->product_id);

        // Validate stock
        if ($product->stock < $request->quantity) {
            return redirect()->back()
                ->withInput()
                ->with('error', "Stok tidak cukup. Stok saat ini: {$product->stock}");
        }

        InventoryMovement::record(
            $product->id,
            'out',
            $request->quantity,
            null,
            $request->notes
        );

        return redirect()->route('admin.inventory.movements.index')
            ->with('success', 'Stok keluar berhasil dicatat.');
    }

    public function show(InventoryMovement $movement)
    {
        $movement->load(['product', 'supplier', 'creator']);
        return view('admin.inventory.movements.show', compact('movement'));
    }

    public function report(Request $request)
    {
        // Default to current month
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Summary
        $totalStockIn = InventoryMovement::stockIn()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        $totalStockOut = InventoryMovement::stockOut()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity');

        // Movement details
        $movements = InventoryMovement::with(['product', 'supplier', 'creator'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        // Low stock products
        $lowStockProducts = Product::where('stock', '<', 10)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('stock', 0)->get();

        return view('admin.inventory.movements.report', compact(
            'startDate',
            'endDate',
            'totalStockIn',
            'totalStockOut',
            'movements',
            'lowStockProducts',
            'outOfStockProducts'
        ));
    }
}
