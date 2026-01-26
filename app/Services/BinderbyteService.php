<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class BinderbyteService
{
    protected $client;
    protected $apiKey;
    protected $baseUrl = 'https://api.binderbyte.com/v1';

    public function __construct()
    {
        $this->apiKey = env('BINDERBYTE_API_KEY');
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 30,
        ]);
    }

    /**
     * Track shipment by AWB (Airway Bill / Resi)
     *
     * @param string $courier Courier code (jne, jnt, sicepat, idexpress, etc)
     * @param string $awb Airway bill / Resi number
     * @param string|null $phone Phone number (required for sicepat and idexpress)
     */
    public function track($courier, $awb, $phone = null)
    {
        try {
            $query = [
                'api_key' => $this->apiKey,
                'courier' => strtolower($courier),
                'awb' => $awb
            ];

            // SiCepat and ID Express require phone number
            if (in_array(strtolower($courier), ['sicepat', 'idexpress']) && $phone) {
                $query['phone'] = $phone;
            }

            $response = $this->client->get('/track', [
                'query' => $query
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['status']) && $data['status'] == 200) {
                return [
                    'success' => true,
                    'data' => $data['data'] ?? null
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Failed to track shipment'
            ];

        } catch (GuzzleException $e) {
            Log::error('Binderbyte API Error', [
                'courier' => $courier,
                'awb' => $awb,
                'phone' => $phone,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'API request failed: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Check if courier requires phone number for tracking
     */
    public function requiresPhone($courier)
    {
        return in_array(strtolower($courier), ['sicepat', 'idexpress']);
    }

    public function getSupportedCouriers()
    {
        return [
            'jne' => 'JNE',
            'jnt' => 'J&T Express',
            'sicepat' => 'SiCepat',
            'tiki' => 'TIKI',
            'pos' => 'POS Indonesia',
            'ninja' => 'Ninja Xpress',
            'anteraja' => 'AnterAja',
            'idexpress' => 'ID Express',
            'lion' => 'Lion Parcel',
        ];
    }

    public function parseStatus($trackingData)
    {
        if (!$trackingData || !isset($trackingData['summary'])) {
            return 'pending';
        }

        $status = strtolower($trackingData['summary']['status'] ?? '');
        $description = strtolower($trackingData['summary']['desc'] ?? '');

        if (str_contains($status, 'delivered') || str_contains($description, 'delivered')) {
            return 'delivered';
        }

        if (str_contains($status, 'transit') || str_contains($description, 'transit')) {
            return 'in_transit';
        }

        return 'on_process';
    }

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
}
