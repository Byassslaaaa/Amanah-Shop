<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppInventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    protected $inventoryService;

    public function __construct(WhatsAppInventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * Handle incoming WhatsApp webhook
     *
     * This endpoint receives messages from WhatsApp Business API
     * and processes inventory management commands
     */
    public function handle(Request $request)
    {
        try {
            // Log incoming webhook for debugging
            Log::info('WhatsApp Webhook Received', [
                'payload' => $request->all()
            ]);

            // Webhook verification (GET request from WhatsApp)
            if ($request->isMethod('get')) {
                return $this->verifyWebhook($request);
            }

            // Extract message data from WhatsApp webhook payload
            // Adjust these paths based on your WhatsApp provider's webhook format
            $message = $this->extractMessage($request);
            $senderPhone = $this->extractSenderPhone($request);

            if (!$message || !$senderPhone) {
                Log::warning('WhatsApp webhook missing message or sender', [
                    'message' => $message,
                    'sender' => $senderPhone
                ]);
                return response()->json(['status' => 'ignored'], 200);
            }

            // Process the message through inventory service
            $result = $this->inventoryService->processMessage($message, $senderPhone);

            // Send response back to WhatsApp
            if (config('services.whatsapp.auto_reply')) {
                $this->sendWhatsAppReply($senderPhone, $result['message']);
            }

            return response()->json([
                'status' => 'success',
                'processed' => $result['success'],
                'message' => $result['message']
            ], 200);

        } catch (\Exception $e) {
            Log::error('WhatsApp Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error'
            ], 500);
        }
    }

    /**
     * Verify webhook (for WhatsApp Business API setup)
     */
    private function verifyWebhook(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        $verifyToken = config('services.whatsapp.verify_token');

        if ($mode === 'subscribe' && $token === $verifyToken) {
            Log::info('WhatsApp webhook verified successfully');
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        Log::warning('WhatsApp webhook verification failed');
        return response('Forbidden', 403);
    }

    /**
     * Extract message text from webhook payload
     *
     * Format varies by provider:
     * - Official WhatsApp Business API
     * - Twilio
     * - Vonage
     * - Other providers
     */
    private function extractMessage(Request $request)
    {
        // Official WhatsApp Business API format
        if ($request->has('entry.0.changes.0.value.messages.0.text.body')) {
            return $request->input('entry.0.changes.0.value.messages.0.text.body');
        }

        // Twilio WhatsApp format
        if ($request->has('Body')) {
            return $request->input('Body');
        }

        // Vonage/Nexmo format
        if ($request->has('message.content.text')) {
            return $request->input('message.content.text');
        }

        // Generic fallback
        if ($request->has('message')) {
            return $request->input('message');
        }

        return null;
    }

    /**
     * Extract sender phone number from webhook payload
     */
    private function extractSenderPhone(Request $request)
    {
        // Official WhatsApp Business API format
        if ($request->has('entry.0.changes.0.value.messages.0.from')) {
            return $request->input('entry.0.changes.0.value.messages.0.from');
        }

        // Twilio WhatsApp format
        if ($request->has('From')) {
            // Twilio format: whatsapp:+628123456789
            $from = $request->input('From');
            return str_replace('whatsapp:', '', $from);
        }

        // Vonage/Nexmo format
        if ($request->has('from.number')) {
            return $request->input('from.number');
        }

        // Generic fallback
        if ($request->has('phone')) {
            return $request->input('phone');
        }

        return null;
    }

    /**
     * Send reply message to WhatsApp
     *
     * This is a placeholder - implement based on your WhatsApp provider
     */
    private function sendWhatsAppReply($phone, $message)
    {
        // Example implementation for different providers:

        // 1. Official WhatsApp Business API
        if (config('services.whatsapp.provider') === 'official') {
            $this->sendViaOfficialAPI($phone, $message);
        }

        // 2. Twilio
        elseif (config('services.whatsapp.provider') === 'twilio') {
            $this->sendViaTwilio($phone, $message);
        }

        // 3. Vonage
        elseif (config('services.whatsapp.provider') === 'vonage') {
            $this->sendViaVonage($phone, $message);
        }

        // Add other providers as needed
    }

    /**
     * Send via Official WhatsApp Business API
     */
    private function sendViaOfficialAPI($phone, $message)
    {
        $apiUrl = config('services.whatsapp.api_url');
        $accessToken = config('services.whatsapp.access_token');
        $phoneNumberId = config('services.whatsapp.phone_number_id');

        $response = \Http::withToken($accessToken)
            ->post("{$apiUrl}/{$phoneNumberId}/messages", [
                'messaging_product' => 'whatsapp',
                'to' => $phone,
                'type' => 'text',
                'text' => [
                    'body' => $message
                ]
            ]);

        Log::info('WhatsApp reply sent', [
            'phone' => $phone,
            'status' => $response->status()
        ]);
    }

    /**
     * Send via Twilio
     */
    private function sendViaTwilio($phone, $message)
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        $from = config('services.twilio.whatsapp_from');

        $twilio = new \Twilio\Rest\Client($accountSid, $authToken);

        $twilio->messages->create(
            "whatsapp:{$phone}",
            [
                'from' => "whatsapp:{$from}",
                'body' => $message
            ]
        );

        Log::info('WhatsApp reply sent via Twilio', ['phone' => $phone]);
    }

    /**
     * Send via Vonage
     */
    private function sendViaVonage($phone, $message)
    {
        $apiKey = config('services.vonage.api_key');
        $apiSecret = config('services.vonage.api_secret');
        $from = config('services.vonage.whatsapp_number');

        $response = \Http::post('https://messages-sandbox.nexmo.com/v1/messages', [
            'from' => ['type' => 'whatsapp', 'number' => $from],
            'to' => ['type' => 'whatsapp', 'number' => $phone],
            'message' => [
                'content' => [
                    'type' => 'text',
                    'text' => $message
                ]
            ]
        ])->withBasicAuth($apiKey, $apiSecret);

        Log::info('WhatsApp reply sent via Vonage', [
            'phone' => $phone,
            'status' => $response->status()
        ]);
    }
}
