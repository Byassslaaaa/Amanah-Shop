<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RajaOngkirService;
use Illuminate\Support\Facades\Cache;

class TestShippingApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shipping:test
                            {action=menu : Action to perform (menu|provinces|cities|search|clear-cache)}
                            {--province= : Province ID to test cities}
                            {--keyword= : Keyword to search destinations}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test and debug Komerce Shipping API integration';

    protected $rajaOngkir;

    public function __construct(RajaOngkirService $rajaOngkir)
    {
        parent::__construct();
        $this->rajaOngkir = $rajaOngkir;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'menu':
                $this->showMenu();
                break;
            case 'provinces':
                $this->testProvinces();
                break;
            case 'cities':
                $this->testCities();
                break;
            case 'search':
                $this->testSearch();
                break;
            case 'clear-cache':
                $this->clearCache();
                break;
            default:
                $this->error("Unknown action: {$action}");
                $this->showMenu();
        }
    }

    protected function showMenu()
    {
        $this->info('=== Komerce Shipping API Test Tool ===');
        $this->newLine();
        $this->line('Available commands:');
        $this->line('  php artisan shipping:test provinces          - List all provinces');
        $this->line('  php artisan shipping:test cities --province=35  - Test cities for province (e.g., 35 = Jawa Timur)');
        $this->line('  php artisan shipping:test search --keyword=surabaya - Search destinations by keyword');
        $this->line('  php artisan shipping:test clear-cache        - Clear shipping cache');
        $this->newLine();
    }

    protected function testProvinces()
    {
        $this->info('=== Testing Province List ===');
        $provinces = $this->rajaOngkir->getProvinces();

        $this->table(['ID', 'Province Name'], collect($provinces)->map(function($p) {
            return [$p['province_id'], $p['province']];
        })->toArray());

        $this->info('Total provinces: ' . count($provinces));
    }

    protected function testCities()
    {
        $provinceId = $this->option('province');

        if (!$provinceId) {
            $this->error('Please specify province ID with --province option');
            $this->line('Example: php artisan shipping:test cities --province=35');
            return;
        }

        $this->info("=== Testing Cities for Province ID: {$provinceId} ===");

        // Get province name
        $provinces = $this->rajaOngkir->getProvinces();
        $province = collect($provinces)->firstWhere('province_id', $provinceId);

        if (!$province) {
            $this->error("Province not found with ID: {$provinceId}");
            return;
        }

        $this->info("Province: {$province['province']}");
        $this->newLine();

        // Test with progress bar
        $this->info('Fetching cities from API...');
        $bar = $this->output->createProgressBar(1);
        $bar->start();

        $cities = $this->rajaOngkir->getCities($provinceId);

        $bar->finish();
        $this->newLine(2);

        if (empty($cities)) {
            $this->error('No cities found! Check logs for details.');
            $this->warn('Logs location: storage/logs/laravel.log');
            return;
        }

        $this->info('Found ' . count($cities) . ' destinations');
        $this->newLine();

        // Show first 20 cities
        $displayCities = array_slice($cities, 0, 20);
        $this->table(
            ['Dest ID', 'Type', 'City/District/Subdistrict', 'Postal Code'],
            collect($displayCities)->map(function($c) {
                return [
                    $c['city_id'],
                    $c['type'],
                    $c['city_name'],
                    $c['postal_code']
                ];
            })->toArray()
        );

        if (count($cities) > 20) {
            $this->info('... and ' . (count($cities) - 20) . ' more destinations');
        }

        // Show cache status
        $cacheKey = "collaborator_cities_{$provinceId}";
        $this->newLine();
        $this->info("Cache key: {$cacheKey}");
        $this->info('Cache status: ' . (Cache::has($cacheKey) ? 'Cached' : 'Not cached'));
    }

    protected function testSearch()
    {
        $keyword = $this->option('keyword');

        if (!$keyword) {
            $this->error('Please specify keyword with --keyword option');
            $this->line('Example: php artisan shipping:test search --keyword=surabaya');
            return;
        }

        $this->info("=== Searching destinations with keyword: {$keyword} ===");
        $this->newLine();

        $results = $this->rajaOngkir->searchDestination($keyword);

        if (empty($results)) {
            $this->error('No results found! Check logs for API response details.');
            $this->warn('Logs location: storage/logs/laravel.log');
            return;
        }

        $this->info('Found ' . count($results) . ' results');
        $this->newLine();

        // Show first 20 results
        $displayResults = array_slice($results, 0, 20);
        $this->table(
            ['ID', 'City', 'District', 'Subdistrict', 'Postal Code'],
            collect($displayResults)->map(function($r) {
                return [
                    $r['id'] ?? '-',
                    $r['city_name'] ?? '-',
                    $r['district_name'] ?? '-',
                    $r['subdistrict_name'] ?? '-',
                    $r['zip_code'] ?? '-',
                ];
            })->toArray()
        );

        if (count($results) > 20) {
            $this->info('... and ' . (count($results) - 20) . ' more results');
        }
    }

    protected function clearCache()
    {
        $this->info('=== Clearing Shipping Cache ===');

        // Clear province cache
        Cache::forget('collaborator_provinces');
        $this->line('✓ Cleared province cache');

        // Clear all city caches (province IDs from 11 to 94)
        $provinceIds = ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21',
                        '31', '32', '33', '34', '35', '36',
                        '51', '52', '53',
                        '61', '62', '63', '64', '65',
                        '71', '72', '73', '74', '75', '76',
                        '81', '82',
                        '91', '94'];

        $cleared = 0;
        foreach ($provinceIds as $provinceId) {
            $cacheKey = "collaborator_cities_{$provinceId}";
            if (Cache::has($cacheKey)) {
                Cache::forget($cacheKey);
                $cleared++;
            }
        }

        $this->line("✓ Cleared {$cleared} city caches");
        $this->newLine();
        $this->info('All shipping caches cleared successfully!');
        $this->info('Next API call will fetch fresh data from Komerce API.');
    }
}
