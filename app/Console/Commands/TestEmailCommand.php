<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\System\Setting;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing email configuration...');
        
        try {
            // Get admin email
            $adminEmail = Setting::get('admin_email');
            $this->info("Admin email: {$adminEmail}");

            if (!$adminEmail) {
                $this->error('Admin email not set in settings!');
                return;
            }

            // Send test email
            Mail::send('emails.contact', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'subject' => 'Test Email',
                'messageContent' => 'This is a test message from the contact form.',
            ], function ($mail) use ($adminEmail) {
                $mail->to($adminEmail)
                     ->subject('Test: Pesan Kontak Amanah Shop')
                     ->from(config('mail.from.address'), 'Test User')
                     ->replyTo('test@example.com', 'Test User');
            });
            
            $this->info('✅ Email sent successfully!');
            $this->info('Check storage/logs/laravel.log for email content (using log driver)');
            
        } catch (\Exception $e) {
            $this->error("❌ Email failed: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
        }
    }
}
