<?php

namespace App\Http\Controllers\Admin\Village;

use App\Http\Controllers\Controller;
use App\Models\Village;
use Illuminate\Http\Request;

class ShippingSettingsController extends Controller
{
    /**
     * Show shipping settings form for village admin
     */
    public function index()
    {
        $user = auth()->user();

        // Only admin with village can access this
        if (!$user->village_id) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini');
        }

        $village = Village::findOrFail($user->village_id);

        return view('admin.villages.shipping-settings', compact('village'));
    }

    /**
     * Update shipping settings
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        if (!$user->village_id) {
            abort(403);
        }

        $validated = $request->validate([
            'origin_province_id' => 'required|string',
            'origin_province_name' => 'required|string',
            'origin_city_id' => 'required|string',
            'origin_city_name' => 'required|string',
            'origin_postal_code' => 'nullable|string|max:10',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $village = Village::findOrFail($user->village_id);
        $village->update($validated);

        return redirect()->route('admin.shipping-settings.index')
            ->with('success', 'Lokasi pengiriman berhasil diperbarui');
    }

    /**
     * Clear shipping cache
     */
    public function clearCache()
    {
        $user = auth()->user();

        if (!$user->village_id) {
            abort(403);
        }

        try {
            // Clear province cache
            \Cache::forget('collaborator_provinces');

            // Clear all city caches
            $provinceIds = ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21',
                            '31', '32', '33', '34', '35', '36',
                            '51', '52', '53',
                            '61', '62', '63', '64', '65',
                            '71', '72', '73', '74', '75', '76',
                            '81', '82',
                            '91', '94'];

            foreach ($provinceIds as $provinceId) {
                \Cache::forget("collaborator_cities_{$provinceId}");
            }

            return response()->json([
                'success' => true,
                'message' => 'Cache berhasil dibersihkan. Silakan refresh halaman untuk memuat data terbaru.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membersihkan cache: ' . $e->getMessage()
            ], 500);
        }
    }
}
