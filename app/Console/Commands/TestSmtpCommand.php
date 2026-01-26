<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\System\Setting;

class TestSmtpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:smtp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMTP email configuration from database settings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Testing SMTP configuration from database...');
        
        try {
            // Get all SMTP settings
            $smtpHost = Setting::get('smtp_host');
            $smtpPort = Setting::get('smtp_port');
            $smtpUsername = Setting::get('smtp_username');
            $smtpPassword = Setting::get('smtp_password');
            $smtpEncryption = Setting::get('smtp_encryption');
            $villageHeadEmail = Setting::get('village_head_email');
            
            // Display current settings
            $this->info("\n📋 Current SMTP Settings:");
            $this->line("Host: " . ($smtpHost ?: 'Not set'));
            $this->line("Port: " . ($smtpPort ?: 'Not set'));
            $this->line("Username: " . ($smtpUsername ?: 'Not set'));
            $this->line("Password: " . ($smtpPassword ? str_repeat('*', strlen($smtpPassword)) : 'Not set'));
            $this->line("Encryption: " . ($smtpEncryption ?: 'Not set'));
            $this->line("Village Head Email: " . ($villageHeadEmail ?: 'Not set'));
            
            if (!$villageHeadEmail) {
                $this->error('❌ Village head email not set in settings!');
                return;
            }
            
            if (!$smtpHost || !$smtpUsername || !$smtpPassword) {
                $this->warn('⚠️  SMTP settings incomplete. Using log driver instead.');
                $this->info('📧 Email will be logged to storage/logs/laravel.log');
            } else {
                $this->info('✅ SMTP settings found. Attempting to send real email...');
            }
            
            // Send test email
            Mail::send('emails.contact', [
                'name' => 'Test Admin',
                'email' => 'admin@test.com',
                'subject' => 'Test SMTP Configuration',
                'messageContent' => 'This is a test email to verify SMTP configuration is working properly. If you receive this email, the SMTP setup is successful!',
            ], function ($mail) use ($villageHeadEmail) {
                $mail->to($villageHeadEmail)
                     ->subject('✅ Test: SMTP Configuration Berhasil!')
                     ->from(config('mail.from.address'), 'Test SMTP System')
                     ->replyTo('admin@test.com', 'Test Admin');
            });
            
            $this->info('✅ Email sent successfully!');
            
            if (!$smtpHost || !$smtpUsername || !$smtpPassword) {
                $this->info('📝 Check storage/logs/laravel.log for email content (using log driver)');
            } else {
                $this->info('📧 Check inbox at: ' . $villageHeadEmail);
            }
            
        } catch (\Exception $e) {
            $this->error("❌ Email failed: " . $e->getMessage());
            
            if (str_contains($e->getMessage(), 'Authentication failed')) {
                $this->warn("\n💡 Tips untuk fix Gmail SMTP:");
                $this->line("1. Pastikan 2-Factor Authentication aktif");
                $this->line("2. Generate App Password khusus untuk Mail");
                $this->line("3. Gunakan App Password (16-digit) bukan password biasa");
                $this->line("4. Host: smtp.gmail.com, Port: 587, Encryption: tls");
            }
        }
    }
}
