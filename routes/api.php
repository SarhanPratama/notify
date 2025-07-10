<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WhatsAppWebhookController;

    Route::get('/webhook', [WhatsAppWebhookController::class, 'verifyWebhook']);
    Route::post('/webhook', [WhatsAppWebhookController::class, 'handleIncoming']);
