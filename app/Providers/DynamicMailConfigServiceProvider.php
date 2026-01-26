<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use App\Models\System\Setting;

class DynamicMailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            // Check if database exists and settings table exists
            if (app()->runningInConsole() && !$this->databaseExists()) {
                return;
            }

            $this->configureMail();
        } catch (\Exception $e) {
            // Silently fail if database is not available (e.g., during migrations)
            return;
        }
    }

    private function databaseExists(): bool
    {
        try {
            \Schema::hasTable('settings');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function configureMail(): void
    {
        // Get SMTP settings from database
        $smtpHost = Setting::get('smtp_host');
        $smtpPort = Setting::get('smtp_port');
        $smtpUsername = Setting::get('smtp_username');
        $smtpPassword = Setting::get('smtp_password');
        $smtpEncryption = Setting::get('smtp_encryption');
        $mailFromName = Setting::get('mail_from_name');

        // Only configure SMTP if all required settings are present
        if ($smtpHost && $smtpUsername && $smtpPassword) {
            Config::set('mail.mailers.smtp', [
                'transport' => 'smtp',
                'host' => $smtpHost,
                'port' => $smtpPort ?: 587,
                'encryption' => $smtpEncryption ?: 'tls',
                'username' => $smtpUsername,
                'password' => $smtpPassword,
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ]);

            Config::set('mail.default', 'smtp');
            
            if ($mailFromName) {
                Config::set('mail.from.name', $mailFromName);
            }
        }
    }
}
