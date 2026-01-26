<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class BiteshipService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('biteship.api_key');
        $this->baseUrl = config('biteship.base_url', 'https://api.biteship.com/v1');

        // Check if API key is set
        if (empty($this->apiKey)) {
            \Log::warning('Biteship API key is not configured. Please set BITESHIP_API_KEY in .env file');
        }

        // Ensure base_uri ends with slash for proper path resolution
        $baseUri = rtrim($this->baseUrl, '/') . '/';

        $this->client = new Client([
            'base_uri' => $baseUri,
            'timeout' => 30,
            'headers' => [
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * Get shipping rates from multiple couriers
     *
     * @param array $params
     * @return array
     */
    public function getShippingRates($params)
    {
        try {
            $payload = [
                'origin_latitude' => $params['origin_latitude'],
                'origin_longitude' => $params['origin_longitude'],
                'destination_latitude' => $params['destination_latitude'],
                'destination_longitude' => $params['destination_longitude'],
                'couriers' => $params['couriers'] ?? 'jne,jnt,sicepat,tiki,anteraja,ninja,lion,idexpress',
                'items' => $params['items'] ?? [],
            ];

            // Optional postal codes
            if (isset($params['origin_postal_code'])) {
                $payload['origin_postal_code'] = $params['origin_postal_code'];
            }
            if (isset($params['destination_postal_code'])) {
                $payload['destination_postal_code'] = $params['destination_postal_code'];
            }

            Log::info('Biteship Rates Request', $payload);

            $response = $this->client->post('rates/couriers', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            Log::info('Biteship Rates Response', ['data' => $data]);

            if (isset($data['success']) && $data['success']) {
                return [
                    'success' => true,
                    'data' => $data['pricing'] ?? []
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to get shipping rates'
            ];

        } catch (GuzzleException $e) {
            Log::error('Biteship API Error - Get Rates', [
                'params' => $params,
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Create an order/shipment
     *
     * @param array $params
     * @return array
     */
    public function createOrder($params)
    {
        try {
            $payload = [
                'shipper_contact_name' => $params['shipper_name'],
                'shipper_contact_phone' => $params['shipper_phone'],
                'shipper_contact_email' => $params['shipper_email'] ?? '',
                'shipper_organization' => $params['shipper_organization'] ?? 'SiDesa',
                'origin_contact_name' => $params['origin_name'],
                'origin_contact_phone' => $params['origin_phone'],
                'origin_address' => $params['origin_address'],
                'origin_note' => $params['origin_note'] ?? '',
                'origin_postal_code' => $params['origin_postal_code'],
                'origin_coordinate' => [
                    'latitude' => $params['origin_latitude'],
                    'longitude' => $params['origin_longitude']
                ],
                'destination_contact_name' => $params['destination_name'],
                'destination_contact_phone' => $params['destination_phone'],
                'destination_contact_email' => $params['destination_email'] ?? '',
                'destination_address' => $params['destination_address'],
                'destination_postal_code' => $params['destination_postal_code'],
                'destination_note' => $params['destination_note'] ?? '',
                'destination_coordinate' => [
                    'latitude' => $params['destination_latitude'],
                    'longitude' => $params['destination_longitude']
                ],
                'courier_company' => $params['courier_company'],
                'courier_type' => $params['courier_type'],
                'delivery_type' => $params['delivery_type'] ?? 'now',
                'items' => $params['items'],
            ];

            // Optional reference_id
            if (isset($params['reference_id'])) {
                $payload['reference_id'] = $params['reference_id'];
            }

            Log::info('Biteship Create Order Request', $payload);

            $response = $this->client->post('orders', [
                'json' => $payload
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            Log::info('Biteship Create Order Response', ['data' => $data]);

            if (isset($data['success']) && $data['success']) {
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to create order'
            ];

        } catch (GuzzleException $e) {
            Log::error('Biteship API Error - Create Order', [
                'params' => $params,
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Track shipment by order ID or waybill ID
     *
     * @param string $trackingId
     * @return array
     */
    public function track($trackingId)
    {
        try {
            $response = $this->client->get("trackings/{$trackingId}");

            $data = json_decode($response->getBody()->getContents(), true);

            Log::info('Biteship Tracking Response', ['data' => $data]);

            if (isset($data['success']) && $data['success']) {
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to track shipment'
            ];

        } catch (GuzzleException $e) {
            Log::error('Biteship API Error - Track', [
                'tracking_id' => $trackingId,
                'error' => $e->getMessage(),
                'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get available couriers
     *
     * @return array
     */
    public function getSupportedCouriers()
    {
        return config('biteship.couriers');
    }

    /**
     * Parse tracking status
     *
     * @param array $trackingData
     * @return string
     */
    public function parseStatus($trackingData)
    {
        if (!isset($trackingData['status'])) {
            return 'pending';
        }

        $status = strtolower($trackingData['status']);

        // Biteship status mapping
        // confirmed, allocated, picking_up, picked, dropping_off, delivered, cancelled, rejected, courierNotFound, returned

        $statusMap = [
            'delivered' => 'delivered',
            'dropping_off' => 'in_transit',
            'picked' => 'in_transit',
            'picking_up' => 'on_process',
            'allocated' => 'on_process',
            'confirmed' => 'on_process',
            'cancelled' => 'failed',
            'rejected' => 'failed',
            'couriernotfound' => 'failed',
            'returned' => 'failed',
        ];

        return $statusMap[$status] ?? 'pending';
    }

    /**
     * Get status label in Indonesian
     *
     * @param string $status
     * @return string
     */
    public function getStatusLabel($status)
    {
        $labels = [
            'pending' => 'Menunggu Pengiriman',
            'on_process' => 'Sedang Diproses',
            'in_transit' => 'Dalam Perjalanan',
            'delivered' => 'Terkirim',
            'failed' => 'Gagal/Dibatalkan'
        ];

        return $labels[$status] ?? 'Status Tidak Diketahui';
    }

    /**
     * Get tracking history from Biteship response
     *
     * @param array $trackingData
     * @return array
     */
    public function getTrackingHistory($trackingData)
    {
        if (!isset($trackingData['history'])) {
            return [];
        }

        // Format history for consistent display
        $history = [];
        foreach ($trackingData['history'] as $item) {
            $history[] = [
                'date' => $item['updated_at'] ?? $item['created_at'] ?? null,
                'description' => $item['note'] ?? $item['status'] ?? '',
                'status' => $item['status'] ?? '',
            ];
        }

        return $history;
    }

    /**
     * Retrieve maps from origin to destination
     *
     * @param array $params
     * @return array
     */
    public function getMaps($params)
    {
        try {
            $query = [
                'origin_latitude' => $params['origin_latitude'],
                'origin_longitude' => $params['origin_longitude'],
                'destination_latitude' => $params['destination_latitude'],
                'destination_longitude' => $params['destination_longitude'],
            ];

            $response = $this->client->get('maps/areas', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['success']) && $data['success']) {
                return [
                    'success' => true,
                    'data' => $data
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to get map data'
            ];

        } catch (GuzzleException $e) {
            Log::error('Biteship API Error - Get Maps', [
                'params' => $params,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Search postal code by area name
     *
     * @param string $search
     * @return array
     */
    public function searchPostalCode($search)
    {
        // Check if API key is configured
        if (empty($this->apiKey)) {
            return [
                'success' => false,
                'message' => 'Biteship API key is not configured. Please contact administrator.'
            ];
        }

        try {
            $response = $this->client->get('maps/areas', [
                'query' => [
                    'countries' => 'ID',
                    'input' => $search,
                    'type' => 'single'
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            Log::info('Biteship Search Postal Code Response', [
                'search' => $search,
                'response' => $data
            ]);

            if (isset($data['success']) && $data['success']) {
                $areas = $data['areas'] ?? [];

                // Biteship /maps/areas doesn't return coordinates
                // We need to add estimated coordinates based on city/district
                $areasWithCoordinates = array_map(function($area) {
                    // Estimate coordinates based on city (rough estimates for Indonesia)
                    $coordinates = $this->estimateCoordinates($area);

                    return array_merge($area, [
                        'latitude' => $coordinates['latitude'],
                        'longitude' => $coordinates['longitude']
                    ]);
                }, $areas);

                return [
                    'success' => true,
                    'data' => $areasWithCoordinates
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to search postal code',
                'error' => $data['error'] ?? null
            ];

        } catch (GuzzleException $e) {
            $errorMessage = $e->getMessage();
            $responseBody = $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null;

            Log::error('Biteship API Error - Search Postal Code', [
                'search' => $search,
                'error' => $errorMessage,
                'response' => $responseBody
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $errorMessage
            ];
        }
    }

    /**
     * Estimate coordinates for Indonesian cities/districts
     * This is a rough estimation based on major cities
     *
     * @param array $area
     * @return array
     */
    private function estimateCoordinates($area)
    {
        $city = strtolower($area['administrative_division_level_2_name'] ?? '');
        $district = strtolower($area['administrative_division_level_3_name'] ?? '');

        // Major city coordinates (approximate)
        $cityCoordinates = [
            'jakarta pusat' => ['latitude' => -6.1753924, 'longitude' => 106.8271528],
            'jakarta selatan' => ['latitude' => -6.2608232, 'longitude' => 106.8106038],
            'jakarta utara' => ['latitude' => -6.1383985, 'longitude' => 106.8630683],
            'jakarta barat' => ['latitude' => -6.1675057, 'longitude' => 106.7617565],
            'jakarta timur' => ['latitude' => -6.2629164, 'longitude' => 106.8998808],
            'bandung' => ['latitude' => -6.9174639, 'longitude' => 107.6191228],
            'surabaya' => ['latitude' => -7.2574719, 'longitude' => 112.7520883],
            'medan' => ['latitude' => 3.5951956, 'longitude' => 98.6722227],
            'semarang' => ['latitude' => -6.9932713, 'longitude' => 110.4203583],
            'yogyakarta' => ['latitude' => -7.7955798, 'longitude' => 110.3694896],
            'makassar' => ['latitude' => -5.1477214, 'longitude' => 119.4322323],
            'palembang' => ['latitude' => -2.9760735, 'longitude' => 104.7754307],
            'tangerang' => ['latitude' => -6.1783917, 'longitude' => 106.6319169],
            'bekasi' => ['latitude' => -6.2382597, 'longitude' => 106.9756314],
            'depok' => ['latitude' => -6.4025220, 'longitude' => 106.7941680],
            'bogor' => ['latitude' => -6.5971469, 'longitude' => 106.8060388],
        ];

        // Try to find matching city
        foreach ($cityCoordinates as $cityName => $coords) {
            if (str_contains($city, $cityName)) {
                return $coords;
            }
        }

        // Default to Jakarta center if no match
        return ['latitude' => -6.2087634, 'longitude' => 106.8450789];
    }
}
