<?php

namespace App\Http\Controllers\Admin\Credit;

use App\Http\Controllers\Controller;
use App\Models\Credit\InstallmentPlan;
use Illuminate\Http\Request;

class InstallmentPlanController extends Controller
{
    /**
     * Display a listing of installment plans
     */
    public function index()
    {
        $plans = InstallmentPlan::orderBy('display_order')->get();

        return view('admin.credit.plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new installment plan
     */
    public function create()
    {
        return view('admin.credit.plans.create');
    }

    /**
     * Store a newly created installment plan
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'months' => 'required|integer|min:1|max:120',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        InstallmentPlan::create($validated);

        return redirect()->route('admin.credit.plans.index')
            ->with('success', 'Paket cicilan berhasil ditambahkan');
    }

    /**
     * Display the specified installment plan
     */
    public function show(InstallmentPlan $plan)
    {
        return view('admin.credit.plans.show', compact('plan'));
    }

    /**
     * Show the form for editing the specified installment plan
     */
    public function edit(InstallmentPlan $plan)
    {
        return view('admin.credit.plans.edit', compact('plan'));
    }

    /**
     * Update the specified installment plan
     */
    public function update(Request $request, InstallmentPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'months' => 'required|integer|min:1|max:120',
            'interest_rate' => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
            'display_order' => 'integer|min:0',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.credit.plans.index')
            ->with('success', 'Paket cicilan berhasil diperbarui');
    }

    /**
     * Remove the specified installment plan
     */
    public function destroy(InstallmentPlan $plan)
    {
        // Check if plan is being used in any orders
        if ($plan->orders()->exists()) {
            return redirect()->back()
                ->with('error', 'Tidak dapat menghapus paket cicilan yang sedang digunakan dalam order');
        }

        $plan->delete();

        return redirect()->route('admin.credit.plans.index')
            ->with('success', 'Paket cicilan berhasil dihapus');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(InstallmentPlan $plan)
    {
        $plan->update(['is_active' => !$plan->is_active]);

        return redirect()->back()
            ->with('success', 'Status paket cicilan berhasil diubah');
    }
}
