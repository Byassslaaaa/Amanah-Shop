<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\TransactionCategory;
use Illuminate\Http\Request;

class TransactionCategoryController extends Controller
{
    public function index()
    {
        $incomeCategories = TransactionCategory::where('type', 'income')
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        $expenseCategories = TransactionCategory::where('type', 'expense')
            ->withCount('transactions')
            ->orderBy('name')
            ->get();

        return view('admin.finance.categories.index', compact('incomeCategories', 'expenseCategories'));
    }

    public function create()
    {
        return view('admin.finance.categories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        TransactionCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori transaksi berhasil ditambahkan.');
    }

    public function edit(TransactionCategory $category)
    {
        return view('admin.finance.categories.edit', compact('category'));
    }

    public function update(Request $request, TransactionCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $category->update([
            'name' => $request->name,
            'type' => $request->type,
            'description' => $request->description,
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori transaksi berhasil diupdate.');
    }

    public function destroy(TransactionCategory $category)
    {
        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return redirect()->route('admin.finance.categories.index')
                ->with('error', 'Kategori tidak dapat dihapus karena masih memiliki transaksi.');
        }

        $category->delete();

        return redirect()->route('admin.finance.categories.index')
            ->with('success', 'Kategori transaksi berhasil dihapus.');
    }
}
