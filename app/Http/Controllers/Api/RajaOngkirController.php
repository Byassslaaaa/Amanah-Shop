<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class RajaOngkirController extends Controller
{
    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Get all provinces
     */
    public function getProvinces()
    {
        $provinces = $this->rajaOngkir->getProvinces();

        return response()->json([
            'success' => true,
            'data' => $provinces
        ]);
    }

    /**
     * Get cities by province ID
     */
    public function getCities(Request $request)
    {
        $provinceId = $request->input('province_id');

        if (!$provinceId) {
            return response()->json([
                'success' => false,
                'message' => 'Province ID is required'
            ], 400);
        }

        $cities = $this->rajaOngkir->getCities($provinceId);

        return response()->json([
            'success' => true,
            'data' => $cities
        ]);
    }

    /**
     * Calculate shipping cost (Collaborator API adapter)
     */
    public function calculateCost(Request $request)
    {
        $request->validate([
            'origin' => 'required', // destination_id for shipper
            'destination' => 'required', // destination_id for receiver
            'weight' => 'required|integer|min:1', // weight in grams
        ]);

        $originDestinationId = $request->input('origin');
        $destinationDestinationId = $request->input('destination');
        $weight = $request->input('weight'); // in grams
        $itemValue = 50000; // Default item value

        // Call Collaborator API
        $costs = $this->rajaOngkir->calculateShippingCost(
            $originDestinationId,
            $destinationDestinationId,
            $weight,
            $itemValue
        );

        // Format response
        $formatted = $this->rajaOngkir->formatShippingServices($costs);

        return response()->json([
            'success' => true,
            'data' => $formatted
        ]);
    }
}
