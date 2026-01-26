<?php

namespace App\Http\Controllers\Admin\Village;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VillageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villages = Village::latest()->paginate(10);
        return view('admin.villages.index', compact('villages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.villages.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'address' => 'nullable|string',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['status'] = $validated['status'] ?? 'active';

        Village::create($validated);

        return redirect()->route('admin.villages.index')
            ->with('success', 'Desa berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Village $village)
    {
        $village->load(['products.category', 'admins']);

        // Statistics
        $totalProducts = $village->products()->count();
        $activeProducts = $village->products()->where('status', 'active')->count();
        $totalAdmins = $village->admins()->count();

        return view('admin.villages.show', compact('village', 'totalProducts', 'activeProducts', 'totalAdmins'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Village $village)
    {
        return view('admin.villages.edit', compact('village'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Village $village)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|string',
            'address' => 'nullable|string',
            'province' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'status' => 'nullable|in:active,inactive',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $village->update($validated);

        return redirect()->route('admin.villages.index')
            ->with('success', 'Desa berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Village $village)
    {
        $village->delete();

        return redirect()->route('admin.villages.index')
            ->with('success', 'Desa berhasil dihapus');
    }

    /**
     * Toggle village status
     */
    public function toggleStatus(Village $village)
    {
        $village->status = $village->status === 'active' ? 'inactive' : 'active';
        $village->save();

        return redirect()->back()->with('success', 'Status desa berhasil diubah');
    }
}
