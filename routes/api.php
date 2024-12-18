<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CandidateProfileController;
use App\Http\Controllers\ShopifyWebhookController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public Routes
Route::post('/resume', [CandidateProfileController::class, 'profile_save']);
Route::get('/list', [CandidateProfileController::class, 'profile_list']);
Route::post('/shopify/webhook/order', [ShopifyWebhookController::class, 'handleOrderWebhook']);
// Route::post('/shopify/webhook/order/test', [ShopifyWebhookController::class, 'handleOrderWebhook']);
