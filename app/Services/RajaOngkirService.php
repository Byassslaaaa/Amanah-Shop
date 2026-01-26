<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = 'AOf8KRGUfbe7d4f21f9d0011KPAvge8W';
        // Use SANDBOX endpoint (API key is for sandbox)
        $this->baseUrl = 'https://api-sandbox.collaborator.komerce.id';
    }

    /**
     * Search destination by keyword (province, city, district, or postal code)
     */
    public function searchDestination($keyword)
    {
        try {
            \Log::info('Searching destination with keyword: ' . $keyword);

            $response = Http::timeout(30)->withHeaders([
                'x-api-key' => $this->apiKey
            ])->get($this->baseUrl . '/tariff/api/v1/destination/search', [
                'keyword' => $keyword
            ]);

            // Log API response
            \Log::info('API Response Status: ' . $response->status(), [
                'keyword' => $keyword,
                'response_size' => strlen($response->body())
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['meta'])) {
                    \Log::info('API Meta:', $data['meta']);
                }

                if (isset($data['data']) && is_array($data['data'])) {
                    $resultCount = count($data['data']);
                    \Log::info("Found {$resultCount} destinations for keyword: {$keyword}");

                    // Return data if available
                    if ($resultCount > 0) {
                        return $data['data'];
                    }
                }

                \Log::warning('No data found for keyword: ' . $keyword, ['response' => $data]);
            } else {
                \Log::error('API request failed for keyword: ' . $keyword, [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return [];
        } catch (\Exception $e) {
            \Log::error('Search Destination Error for keyword: ' . $keyword, [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return [];
        }
    }

    /**
     * Get all provinces (by searching common province names)
     */
    public function getProvinces()
    {
        return Cache::remember('collaborator_provinces', 86400, function () {
            // List provinsi Indonesia
            $provinces = [
                ['province_id' => '11', 'province' => 'Aceh'],
                ['province_id' => '12', 'province' => 'Sumatera Utara'],
                ['province_id' => '13', 'province' => 'Sumatera Barat'],
                ['province_id' => '14', 'province' => 'Riau'],
                ['province_id' => '15', 'province' => 'Jambi'],
                ['province_id' => '16', 'province' => 'Sumatera Selatan'],
                ['province_id' => '17', 'province' => 'Bengkulu'],
                ['province_id' => '18', 'province' => 'Lampung'],
                ['province_id' => '19', 'province' => 'Kepulauan Bangka Belitung'],
                ['province_id' => '21', 'province' => 'Kepulauan Riau'],
                ['province_id' => '31', 'province' => 'DKI Jakarta'],
                ['province_id' => '32', 'province' => 'Jawa Barat'],
                ['province_id' => '33', 'province' => 'Jawa Tengah'],
                ['province_id' => '34', 'province' => 'DI Yogyakarta'],
                ['province_id' => '35', 'province' => 'Jawa Timur'],
                ['province_id' => '36', 'province' => 'Banten'],
                ['province_id' => '51', 'province' => 'Bali'],
                ['province_id' => '52', 'province' => 'Nusa Tenggara Barat'],
                ['province_id' => '53', 'province' => 'Nusa Tenggara Timur'],
                ['province_id' => '61', 'province' => 'Kalimantan Barat'],
                ['province_id' => '62', 'province' => 'Kalimantan Tengah'],
                ['province_id' => '63', 'province' => 'Kalimantan Selatan'],
                ['province_id' => '64', 'province' => 'Kalimantan Timur'],
                ['province_id' => '65', 'province' => 'Kalimantan Utara'],
                ['province_id' => '71', 'province' => 'Sulawesi Utara'],
                ['province_id' => '72', 'province' => 'Sulawesi Tengah'],
                ['province_id' => '73', 'province' => 'Sulawesi Selatan'],
                ['province_id' => '74', 'province' => 'Sulawesi Tenggara'],
                ['province_id' => '75', 'province' => 'Gorontalo'],
                ['province_id' => '76', 'province' => 'Sulawesi Barat'],
                ['province_id' => '81', 'province' => 'Maluku'],
                ['province_id' => '82', 'province' => 'Maluku Utara'],
                ['province_id' => '91', 'province' => 'Papua Barat'],
                ['province_id' => '94', 'province' => 'Papua'],
            ];

            return $provinces;
        });
    }

    /**
     * Get cities by province ID (return all destinations from search)
     * Returns full destinations with subdistrict info for better accuracy
     */
    public function getCities($provinceId)
    {
        // Cache for 24 hours
        return Cache::remember("collaborator_cities_{$provinceId}", 86400, function () use ($provinceId) {
            try {
                // Get province name
                $provinces = $this->getProvinces();
                $province = collect($provinces)->firstWhere('province_id', $provinceId);

                if (!$province) {
                    \Log::warning('Province not found for ID: ' . $provinceId);
                    return [];
                }

                $provinceName = $province['province'];
                \Log::info('Searching cities for province: ' . $provinceName);

                // Try multiple search strategies to get results
                $destinations = $this->searchDestinationsWithFallback($provinceName);

                \Log::info('Found ' . count($destinations) . ' destinations for province: ' . $provinceName);

                // Transform to city format - return all destinations including subdistricts
                $cities = [];
                $uniqueCities = []; // Track unique cities to avoid duplicates

                foreach ($destinations as $dest) {
                    // Skip invalid entries
                    if (empty($dest['id']) || empty($dest['city_name'])) {
                        continue;
                    }

                    // Create label with full address
                    $label = $dest['city_name'];
                    if (!empty($dest['district_name']) && $dest['district_name'] != '-') {
                        $label .= ', ' . $dest['district_name'];
                    }
                    if (!empty($dest['subdistrict_name']) && $dest['subdistrict_name'] != '-') {
                        $label .= ', ' . $dest['subdistrict_name'];
                    }

                    // Use destination_id as unique key
                    $uniqueKey = $dest['id'];
                    if (isset($uniqueCities[$uniqueKey])) {
                        continue; // Skip duplicate
                    }

                    $cities[] = [
                        'city_id' => $dest['id'], // IMPORTANT: This is destination_id for API
                        'province_id' => $provinceId,
                        'type' => (!empty($dest['district_name']) && $dest['district_name'] != '-') ? 'Kab' : 'Kota',
                        'city_name' => $label, // Full label for display
                        'postal_code' => $dest['zip_code'] ?? ''
                    ];

                    $uniqueCities[$uniqueKey] = true;
                }

                // Sort by city name
                usort($cities, function($a, $b) {
                    return strcmp($a['city_name'], $b['city_name']);
                });

                return $cities;
            } catch (\Exception $e) {
                \Log::error('Get Cities Error: ' . $e->getMessage(), [
                    'province_id' => $provinceId,
                    'trace' => $e->getTraceAsString()
                ]);
                return [];
            }
        });
    }

    /**
     * Search destinations with multiple fallback strategies
     */
    protected function searchDestinationsWithFallback($provinceName)
    {
        // Strategy 1: Search with full province name
        $destinations = $this->searchDestination($provinceName);

        // Check if we got good results (more than 10 destinations usually means success)
        if (!empty($destinations) && count($destinations) >= 10) {
            \Log::info("Strategy 1 success: Found " . count($destinations) . " destinations for: " . $provinceName);
            return $destinations;
        }

        // If Strategy 1 gave poor results (< 10), try Strategy 2
        \Log::warning('Strategy 1 gave poor results (' . count($destinations) . ' found) for: ' . $provinceName . ', trying city-based search...');

        // Strategy 2: Try common city names for known provinces with issues
        $majorCities = $this->getMajorCitiesForProvince($provinceName);
        if (!empty($majorCities)) {
            $allDestinations = [];
            foreach ($majorCities as $cityName) {
                $results = $this->searchDestination($cityName);
                if (!empty($results)) {
                    // Filter to only include results where city_name matches
                    $filteredResults = array_filter($results, function($dest) use ($cityName, $majorCities) {
                        $destCityName = strtolower($dest['city_name'] ?? '');
                        $searchCityName = strtolower($cityName);

                        // Check if the destination city_name contains any of the major city names
                        foreach ($majorCities as $majorCity) {
                            if (stripos($destCityName, strtolower($majorCity)) !== false) {
                                return true;
                            }
                        }
                        return false;
                    });

                    if (!empty($filteredResults)) {
                        $allDestinations = array_merge($allDestinations, $filteredResults);
                        \Log::info('Found ' . count($filteredResults) . ' filtered results for city: ' . $cityName);
                    }
                }
            }

            if (!empty($allDestinations)) {
                \Log::info("Strategy 2 success: Found " . count($allDestinations) . " total destinations");
                return $allDestinations;
            }
        }

        // Strategy 3: Try short version (e.g., "Jatim" for "Jawa Timur")
        $shortName = $this->getProvinceShortName($provinceName);
        if ($shortName && $shortName != $provinceName) {
            $destinations = $this->searchDestination($shortName);
            if (!empty($destinations)) {
                \Log::info('Strategy 3 success using short name: ' . $shortName);
                return $destinations;
            }
        }

        // If all else fails, return whatever Strategy 1 found (even if poor)
        if (!empty($destinations)) {
            \Log::warning('All strategies gave poor results, returning Strategy 1 results: ' . count($destinations));
            return $destinations;
        }

        \Log::error('All search strategies failed for province: ' . $provinceName);
        return [];
    }

    /**
     * Get major cities for provinces that commonly have API issues
     */
    protected function getMajorCitiesForProvince($provinceName)
    {
        $cityMap = [
            'Jawa Timur' => [
                'Surabaya', 'Malang', 'Kediri', 'Madiun', 'Jember', 'Probolinggo',
                'Pasuruan', 'Sidoarjo', 'Gresik', 'Mojokerto', 'Blitar', 'Banyuwangi',
                'Lumajang', 'Bondowoso', 'Situbondo', 'Ngawi', 'Magetan', 'Ponorogo',
                'Pacitan', 'Trenggalek', 'Tulungagung', 'Bojonegoro', 'Tuban', 'Lamongan',
                'Bangkalan', 'Sampang', 'Pamekasan', 'Sumenep', 'Nganjuk', 'Jombang'
            ],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Bogor', 'Depok', 'Cirebon', 'Sukabumi', 'Tasikmalaya', 'Garut', 'Cianjur', 'Purwakarta', 'Subang', 'Karawang', 'Indramayu'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Surakarta', 'Yogyakarta', 'Tegal', 'Pekalongan', 'Purwokerto', 'Magelang', 'Salatiga', 'Cilacap', 'Purworejo', 'Kebumen'],
            'Sumatera Utara' => ['Medan', 'Pematangsiantar', 'Binjai', 'Tanjungbalai', 'Padangsidempuan', 'Tebing Tinggi', 'Sibolga'],
            'Sumatera Barat' => ['Padang', 'Bukittinggi', 'Payakumbuh', 'Solok', 'Padang Panjang', 'Pariaman', 'Sawahlunto'],
            'Sumatera Selatan' => ['Palembang', 'Prabumulih', 'Lubuklinggau', 'Pagar Alam', 'Lahat', 'Muara Enim'],
            'Riau' => ['Pekanbaru', 'Dumai', 'Bengkalis', 'Kampar', 'Rokan Hulu', 'Siak'],
            'Jambi' => ['Jambi', 'Sungai Penuh', 'Bungo', 'Muaro Jambi', 'Kerinci'],
            'Bengkulu' => ['Bengkulu', 'Curup', 'Rejang Lebong', 'Bengkulu Utara'],
            'Lampung' => ['Bandar Lampung', 'Metro', 'Lampung Selatan', 'Lampung Timur', 'Lampung Tengah'],
            'Kepulauan Bangka Belitung' => ['Pangkal Pinang', 'Bangka', 'Belitung', 'Belitung Timur'],
            'Kepulauan Riau' => ['Batam', 'Tanjung Pinang', 'Bintan', 'Karimun', 'Natuna'],
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Kepulauan Seribu'],
            'Banten' => ['Tangerang', 'Serang', 'Cilegon', 'Tangerang Selatan', 'Lebak', 'Pandeglang'],
            'DI Yogyakarta' => ['Yogyakarta', 'Bantul', 'Sleman', 'Kulon Progo', 'Gunung Kidul'],
            'Bali' => ['Denpasar', 'Badung', 'Gianyar', 'Tabanan', 'Klungkung', 'Bangli', 'Buleleng', 'Jembrana', 'Karangasem'],
            'Nusa Tenggara Barat' => ['Mataram', 'Bima', 'Lombok Barat', 'Lombok Tengah', 'Lombok Timur', 'Sumbawa', 'Dompu'],
            'Nusa Tenggara Timur' => ['Kupang', 'Ende', 'Maumere', 'Flores Timur', 'Manggarai', 'Sumba Barat', 'Timor Tengah Selatan'],
            'Kalimantan Barat' => ['Pontianak', 'Singkawang', 'Sambas', 'Ketapang', 'Sanggau', 'Sintang'],
            'Kalimantan Tengah' => ['Palangkaraya', 'Sampit', 'Kuala Kapuas', 'Pangkalan Bun'],
            'Kalimantan Selatan' => ['Banjarmasin', 'Banjarbaru', 'Martapura', 'Barabai', 'Rantau'],
            'Kalimantan Timur' => ['Balikpapan', 'Samarinda', 'Bontang', 'Tenggarong', 'Penajam'],
            'Kalimantan Utara' => ['Tarakan', 'Tanjung Selor', 'Bulungan', 'Malinau', 'Nunukan'],
            'Sulawesi Utara' => ['Manado', 'Bitung', 'Tomohon', 'Kotamobagu', 'Minahasa', 'Bolaang Mongondow'],
            'Sulawesi Tengah' => ['Palu', 'Tolitoli', 'Poso', 'Donggala', 'Parigi Moutong', 'Banggai'],
            'Sulawesi Selatan' => ['Makassar', 'Parepare', 'Palopo', 'Gowa', 'Maros', 'Bone', 'Wajo', 'Bulukumba', 'Sinjai', 'Takalar'],
            'Sulawesi Tenggara' => ['Kendari', 'Baubau', 'Kolaka', 'Konawe', 'Buton', 'Muna'],
            'Gorontalo' => ['Gorontalo', 'Limboto', 'Bone Bolango', 'Gorontalo Utara'],
            'Sulawesi Barat' => ['Mamuju', 'Majene', 'Polewali Mandar', 'Mamasa'],
            'Maluku' => ['Ambon', 'Tual', 'Masohi', 'Namlea', 'Seram Bagian Barat', 'Buru'],
            'Maluku Utara' => ['Ternate', 'Tidore', 'Sofifi', 'Tobelo', 'Morotai', 'Halmahera Selatan', 'Halmahera Timur'],
            'Papua Barat' => ['Sorong', 'Manokwari', 'Fak Fak', 'Kaimana', 'Teluk Bintuni', 'Teluk Wondama', 'Raja Ampat'],
            'Papua' => ['Jayapura', 'Merauke', 'Timika', 'Nabire', 'Biak', 'Wamena', 'Jayawijaya'],
        ];

        return $cityMap[$provinceName] ?? [];
    }

    /**
     * Get short name for province
     */
    protected function getProvinceShortName($provinceName)
    {
        $shortNames = [
            'Jawa Timur' => 'Jatim',
            'Jawa Barat' => 'Jabar',
            'Jawa Tengah' => 'Jateng',
            'Sumatera Utara' => 'Sumut',
            'Sumatera Barat' => 'Sumbar',
            'Sumatera Selatan' => 'Sumsel',
            'Kalimantan Timur' => 'Kaltim',
            'Kalimantan Barat' => 'Kalbar',
            'Kalimantan Tengah' => 'Kalteng',
            'Kalimantan Selatan' => 'Kalsel',
            'Sulawesi Utara' => 'Sulut',
            'Sulawesi Tengah' => 'Sulteng',
            'Sulawesi Selatan' => 'Sulsel',
            'Sulawesi Tenggara' => 'Sultra',
        ];

        return $shortNames[$provinceName] ?? null;
    }

    /**
     * Get city detail by city ID
     */
    public function getCityDetail($cityId)
    {
        return Cache::remember("rajaongkir_city_detail_{$cityId}", 86400, function () use ($cityId) {
            try {
                $response = Http::get($this->baseUrl . '/city', [
                    'api_key' => $this->apiKey,
                    'city_id' => $cityId
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (is_array($data) && !empty($data)) {
                        return $data[0] ?? $data;
                    }
                    if (isset($data['data']) && !empty($data['data'])) {
                        return $data['data'][0];
                    }
                }

                return null;
            } catch (\Exception $e) {
                \Log::error('RajaOngkir Get City Detail Error: ' . $e->getMessage());
                return null;
            }
        });
    }

    /**
     * Calculate shipping cost using Collaborator Komerce API
     *
     * @param int $originDestinationId - Origin destination ID
     * @param int $destinationDestinationId - Destination destination ID
     * @param int $weight - Weight in grams
     * @param int $itemValue - Item value for insurance calculation
     */
    public function calculateShippingCost($originDestinationId, $destinationDestinationId, $weight, $itemValue = 50000)
    {
        try {
            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey
            ])->get($this->baseUrl . '/tariff/api/v1/calculate', [
                'shipper_destination_id' => $originDestinationId,
                'receiver_destination_id' => $destinationDestinationId,
                'weight' => $weight / 1000, // Convert grams to kg
                'item_value' => $itemValue,
                'cod' => 'no'
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Debug logging
                \Log::info('Calculate Shipping Request:', [
                    'origin' => $originDestinationId,
                    'destination' => $destinationDestinationId,
                    'weight' => $weight / 1000,
                    'response' => $data
                ]);

                if (isset($data['data'])) {
                    return $data['data'];
                }
            } else {
                \Log::error('Calculate Shipping API Error:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }

            return [];
        } catch (\Exception $e) {
            \Log::error('Calculate Shipping Cost Error: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Get multiple couriers shipping costs (already returns all couriers)
     */
    public function getMultipleCourierCosts($originDestinationId, $destinationDestinationId, $weight, $itemValue = 50000)
    {
        // Collaborator API returns all couriers in one call
        return $this->calculateShippingCost($originDestinationId, $destinationDestinationId, $weight, $itemValue);
    }

    /**
     * Format shipping services for display (Collaborator Komerce format)
     */
    public function formatShippingServices($costs)
    {
        $formatted = [];

        // Collaborator API returns nested arrays: calculate_reguler, calculate_cargo, calculate_instant
        if (is_array($costs)) {
            // Merge all types of shipping
            $allServices = [];

            if (isset($costs['calculate_reguler']) && is_array($costs['calculate_reguler'])) {
                $allServices = array_merge($allServices, $costs['calculate_reguler']);
            }

            if (isset($costs['calculate_cargo']) && is_array($costs['calculate_cargo'])) {
                $allServices = array_merge($allServices, $costs['calculate_cargo']);
            }

            if (isset($costs['calculate_instant']) && is_array($costs['calculate_instant'])) {
                $allServices = array_merge($allServices, $costs['calculate_instant']);
            }

            // Format each service
            foreach ($allServices as $service) {
                $shippingName = $service['shipping_name'] ?? 'Unknown';
                $serviceName = $service['service_name'] ?? '-';
                $shippingCost = $service['shipping_cost'] ?? 0;
                $etd = $service['etd'] ?? '-';

                $formatted[] = [
                    'courier' => $shippingName,
                    'service' => $serviceName,
                    'description' => $shippingName . ' ' . $serviceName,
                    'cost' => $shippingCost,
                    'etd' => $etd ?: '-',
                    'display_name' => $shippingName . ' - ' . $serviceName,
                    'display_cost' => 'Rp ' . number_format($shippingCost, 0, ',', '.'),
                    'display_etd' => ($etd ?: '-') . ' hari'
                ];
            }
        }

        // Sort by cost (ascending)
        usort($formatted, function($a, $b) {
            return $a['cost'] <=> $b['cost'];
        });

        return $formatted;
    }
}
