<?php

namespace App\Http\Controllers\User\Village;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;

class VillageController extends Controller
{
    /**
     * Display all villages
     */
    public function index(Request $request)
    {
        $query = Village::active()->withCount('products');

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter by province
        if ($request->filled('province')) {
            $query->where('province', $request->province);
        }

        $villages = $query->latest()->paginate(12);
        $provinces = Village::active()->distinct()->pluck('province');

        return view('user.villages.index', compact('villages', 'provinces'));
    }

    /**
     * Display village detail with products
     */
    public function show($slug)
    {
        $village = Village::where('slug', $slug)
            ->active()
            ->with(['products' => function($query) {
                $query->active()->inStock()->latest();
            }])
            ->firstOrFail();

        return view('user.villages.show', compact('village'));
    }
}
