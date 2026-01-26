<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\WhatsAppWebhookController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| WhatsApp Webhook Routes
|--------------------------------------------------------------------------
|
| These routes handle incoming WhatsApp messages for inventory management.
| No authentication required as this is called by WhatsApp servers.
| Security is handled via webhook verification token.
|
*/
Route::match(['get', 'post'], '/webhook/whatsapp', [WhatsAppWebhookController::class, 'handle'])
    ->name('api.whatsapp.webhook');
