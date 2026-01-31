<?php

namespace App\Http\Controllers\Admin\Finance;

use App\Http\Controllers\Controller;
use App\Models\Finance\FinancialTransaction;
use App\Models\Finance\TransactionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinancialTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = FinancialTransaction::with(['category', 'creator']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('transaction_number', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        // Summary
        $totalIncome = FinancialTransaction::income()->thisMonth()->sum('amount');
        $totalExpense = FinancialTransaction::expense()->thisMonth()->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $categories = TransactionCategory::where('is_active', true)->orderBy('name')->get();

        return view('admin.finance.transactions.index', compact('transactions', 'totalIncome', 'totalExpense', 'balance', 'categories'));
    }

    public function create()
    {
        $categories = TransactionCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();
        return view('admin.finance.transactions.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:transaction_categories,id',
            'transaction_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string|max:100',
            'receipt_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->except('attachment');
        $data['transaction_number'] = FinancialTransaction::generateTransactionNumber();
        $data['created_by'] = auth()->id();

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $filename = $data['transaction_number'] . '_' . time() . '.' . $file->getClientOriginalExtension();
            $data['attachment'] = $file->storeAs('finance/attachments', $filename, 'public');
        }

        FinancialTransaction::create($data);

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transaksi keuangan berhasil ditambahkan.');
    }

    public function show(FinancialTransaction $transaction)
    {
        $transaction->load(['category', 'creator']);
        return view('admin.finance.transactions.show', compact('transaction'));
    }

    public function edit(FinancialTransaction $transaction)
    {
        $categories = TransactionCategory::where('is_active', true)->orderBy('type')->orderBy('name')->get();
        return view('admin.finance.transactions.edit', compact('transaction', 'categories'));
    }

    public function update(Request $request, FinancialTransaction $transaction)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category_id' => 'required|exists:transaction_categories,id',
            'transaction_date' => 'required|date|before_or_equal:today',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:500',
            'notes' => 'nullable|string',
            'payment_method' => 'nullable|string|max:100',
            'receipt_number' => 'nullable|string|max:100',
            'attachment' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'remove_attachment' => 'boolean'
        ]);

        $data = $request->except(['attachment', 'remove_attachment']);

        // Handle attachment removal
        if ($request->has('remove_attachment') && $transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
            $data['attachment'] = null;
        }

        // Handle new file upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment
            if ($transaction->attachment) {
                Storage::disk('public')->delete($transaction->attachment);
            }

            $file = $request->file('attachment');
            $filename = $transaction->transaction_number . '_' . time() . '.' . $file->getClientOriginalExtension();
            $data['attachment'] = $file->storeAs('finance/attachments', $filename, 'public');
        }

        $transaction->update($data);

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transaksi keuangan berhasil diupdate.');
    }

    public function destroy(FinancialTransaction $transaction)
    {
        // Delete attachment if exists
        if ($transaction->attachment) {
            Storage::disk('public')->delete($transaction->attachment);
        }

        $transaction->delete();

        return redirect()->route('admin.finance.transactions.index')
            ->with('success', 'Transaksi keuangan berhasil dihapus.');
    }

    public function report(Request $request)
    {
        // Default to current month
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Summary
        $totalIncome = FinancialTransaction::income()
            ->dateRange($startDate, $endDate)
            ->sum('amount');

        $totalExpense = FinancialTransaction::expense()
            ->dateRange($startDate, $endDate)
            ->sum('amount');

        $balance = $totalIncome - $totalExpense;

        // Income by category
        $incomeByCategory = FinancialTransaction::income()
            ->dateRange($startDate, $endDate)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function($transactions) {
                return $transactions->sum('amount');
            });

        // Expense by category
        $expenseByCategory = FinancialTransaction::expense()
            ->dateRange($startDate, $endDate)
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map(function($transactions) {
                return $transactions->sum('amount');
            });

        // Transactions list
        $transactions = FinancialTransaction::with(['category', 'creator'])
            ->dateRange($startDate, $endDate)
            ->orderBy('transaction_date', 'desc')
            ->get();

        return view('admin.finance.transactions.report', compact(
            'startDate',
            'endDate',
            'totalIncome',
            'totalExpense',
            'balance',
            'incomeByCategory',
            'expenseByCategory',
            'transactions'
        ));
    }
}
