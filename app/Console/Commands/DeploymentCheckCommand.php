<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeploymentCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deployment:check';

    /**
     * The console description of the console command.
     *
     * @var string
     */
    protected $description = 'Check if application is ready for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 AMANAH SHOP - DEPLOYMENT READINESS CHECK');
        $this->info('==========================================');
        $this->newLine();

        $checks = [
            'Environment Configuration' => $this->checkEnvironment(),
            'Security Configuration' => $this->checkSecurity(),
            'Database Connection' => $this->checkDatabase(),
            'API Credentials' => $this->checkApiCredentials(),
            'Storage Configuration' => $this->checkStorage(),
            'Optimization' => $this->checkOptimization(),
        ];

        $passed = 0;
        $failed = 0;

        foreach ($checks as $category => $results) {
            $this->info("📋 {$category}:");
            foreach ($results as $check => $status) {
                if ($status === true) {
                    $this->line("  ✅ {$check}");
                    $passed++;
                } else {
                    $this->error("  ❌ {$check}");
                    if (is_string($status)) {
                        $this->warn("     → {$status}");
                    }
                    $failed++;
                }
            }
            $this->newLine();
        }

        $total = $passed + $failed;
        $percentage = $total > 0 ? round(($passed / $total) * 100) : 0;

        $this->info("==========================================");
        $this->info("📊 RESULTS: {$passed}/{$total} checks passed ({$percentage}%)");

        if ($failed === 0) {
            $this->info("🎉 All checks passed! Ready for production deployment.");
            return 0;
        } else {
            $this->warn("⚠️  {$failed} check(s) failed. Please fix before deploying to production.");
            return 1;
        }
    }

    protected function checkEnvironment(): array
    {
        $checks = [];

        $checks['APP_ENV is production'] = config('app.env') === 'production'
            ? true
            : 'Set APP_ENV=production in .env';

        $checks['APP_DEBUG is false'] = config('app.debug') === false
            ? true
            : 'Set APP_DEBUG=false in .env (CRITICAL SECURITY RISK!)';

        $checks['APP_KEY is set'] = !empty(config('app.key'))
            ? true
            : 'Run: php artisan key:generate';

        $checks['APP_URL is configured'] = config('app.url') !== 'http://localhost'
            ? true
            : 'Set APP_URL to your domain in .env';

        $checks['Timezone is Asia/Jakarta'] = config('app.timezone') === 'Asia/Jakarta'
            ? true
            : 'Set timezone=Asia/Jakarta in config/app.php';

        return $checks;
    }

    protected function checkSecurity(): array
    {
        $checks = [];

        // Check if security headers middleware exists
        $checks['Security headers middleware registered'] = class_exists(\App\Http\Middleware\SecurityHeadersMiddleware::class);

        // Check CORS configuration
        $corsOrigins = config('cors.allowed_origins', []);
        $checks['CORS not using wildcard'] = !in_array('*', $corsOrigins)
            ? true
            : 'Configure specific origins in config/cors.php';

        return $checks;
    }

    protected function checkDatabase(): array
    {
        $checks = [];

        try {
            DB::connection()->getPDO();
            $checks['Database connection works'] = true;
        } catch (\Exception $e) {
            $checks['Database connection works'] = 'Cannot connect: ' . $e->getMessage();
        }

        $checks['DB_PASSWORD is set'] = !empty(config('database.connections.mysql.password'))
            ? true
            : 'Set strong DB_PASSWORD in .env';

        return $checks;
    }

    protected function checkApiCredentials(): array
    {
        $checks = [];

        // Midtrans
        $checks['Midtrans credentials configured'] =
            !empty(config('services.midtrans.server_key')) &&
            !empty(config('services.midtrans.client_key'))
            ? true
            : 'Configure Midtrans credentials in .env';

        if (config('services.midtrans.is_production')) {
            $checks['Midtrans in production mode'] = true;
        } else {
            $checks['Midtrans in production mode'] = 'Set MIDTRANS_IS_PRODUCTION=true';
        }

        // Biteship
        $checks['Biteship API key configured'] = !empty(config('biteship.api_key'))
            ? true
            : 'Configure Biteship API key in .env';

        $checks['Biteship origin configured'] =
            !empty(config('biteship.shop_origin.latitude')) &&
            !empty(config('biteship.shop_origin.longitude'))
            ? true
            : 'Configure shop coordinates in .env';

        return $checks;
    }

    protected function checkStorage(): array
    {
        $checks = [];

        $checks['Storage link exists'] = is_link(public_path('storage'))
            ? true
            : 'Run: php artisan storage:link';

        $checks['Storage directory writable'] = is_writable(storage_path())
            ? true
            : 'Fix permissions: chmod -R 775 storage';

        return $checks;
    }

    protected function checkOptimization(): array
    {
        $checks = [];

        $checks['Config cached'] = file_exists(bootstrap_path('cache/config.php'))
            ? true
            : 'Run: php artisan config:cache';

        $checks['Routes cached'] = file_exists(bootstrap_path('cache/routes-v7.php'))
            ? true
            : 'Run: php artisan route:cache';

        $checks['Views cached'] = file_exists(storage_path('framework/views'))
            ? true
            : 'Run: php artisan view:cache';

        return $checks;
    }
}
