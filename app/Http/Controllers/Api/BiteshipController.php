<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BiteshipService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BiteshipController extends Controller
{
    protected $biteshipService;

    public function __construct(BiteshipService $biteshipService)
    {
        $this->biteshipService = $biteshipService;
    }

    /**
     * Get shipping rates
     *
     * POST /api/biteship/rates
     * Body: {
     *   "origin_latitude": -6.175110,
     *   "origin_longitude": 106.865036,
     *   "destination_latitude": -6.200000,
     *   "destination_longitude": 106.816666,
     *   "destination_postal_code": "12530",
     *   "couriers": "jne,jnt,sicepat",
     *   "items": [
     *     {
     *       "name": "Product Name",
     *       "description": "Product Description",
     *       "value": 100000,
     *       "length": 10,
     *       "width": 10,
     *       "height": 10,
     *       "weight": 1000,
     *       "quantity": 1
     *     }
     *   ]
     * }
     */
    public function getRates(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin_latitude' => 'required|numeric',
                'origin_longitude' => 'required|numeric',
                'destination_latitude' => 'required|numeric',
                'destination_longitude' => 'required|numeric',
                'destination_postal_code' => 'nullable|string',
                'origin_postal_code' => 'nullable|string',
                'couriers' => 'nullable|string',
                'items' => 'required|array',
                'items.*.name' => 'required|string',
                'items.*.value' => 'required|numeric',
                'items.*.weight' => 'required|numeric',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $result = $this->biteshipService->getShippingRates($validated);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            // Format response for frontend
            $rates = collect($result['data'])->map(function ($rate) {
                return [
                    'courier_code' => $rate['courier_code'] ?? '',
                    'courier_name' => $rate['courier_name'] ?? '',
                    'courier_service_code' => $rate['courier_service_code'] ?? '',
                    'courier_service_name' => $rate['courier_service_name'] ?? '',
                    'description' => $rate['description'] ?? '',
                    'duration' => $rate['duration'] ?? '',
                    'price' => $rate['price'] ?? 0,
                    'type' => $rate['type'] ?? '',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $rates
            ]);

        } catch (\Exception $e) {
            Log::error('Biteship Controller Error - Get Rates', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting shipping rates: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search postal code
     *
     * GET /api/biteship/postal-code/search?q={search_term}
     */
    public function searchPostalCode(Request $request)
    {
        try {
            $search = $request->input('q');

            if (empty($search)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Search term is required'
                ], 400);
            }

            $result = $this->biteshipService->searchPostalCode($search);

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Biteship Controller Error - Search Postal Code', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error searching postal code: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tracking info
     *
     * GET /api/biteship/tracking/{tracking_id}
     */
    public function getTracking($trackingId)
    {
        try {
            $result = $this->biteshipService->track($trackingId);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $result['data']
            ]);

        } catch (\Exception $e) {
            Log::error('Biteship Controller Error - Get Tracking', [
                'tracking_id' => $trackingId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error getting tracking info: ' . $e->getMessage()
            ], 500);
        }
    }
}
