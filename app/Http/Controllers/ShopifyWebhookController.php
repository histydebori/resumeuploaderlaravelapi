<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ShopifyWebhookController extends Controller
{

    // public function handleOrderWebhook(Request $request)
    // {
    //     // Log the raw request data
    //     $orderData = $request->getContent();  // Get raw JSON data
        
    //     // Log the incoming order data
    //     Log::info('Received Order Webhook:', ['order_data' => json_decode($orderData, true)]);

    //     // Respond back with a success message
    //     return response()->json(['status' => 'success', 'message' => 'Webhook received']);
    // }

    
public function handleOrderWebhook(Request $request)
{
    // Shopify sends the data as JSON, decode it
    $orderData = $request->getContent(); // This will give raw JSON data

    // Shopify includes the HMAC signature in the header for validation
    $shopifyHmac = $request->header('X-Shopify-Hmac-Sha256');
    $shopifySecret = env('SHOPIFY_SECRET');  // Store your secret key in .env file

    // Verify the HMAC signature to ensure the request is from Shopify
    $calculatedHmac = base64_encode(hash_hmac('sha256', $orderData, $shopifySecret, true));

    // Check if the HMAC matches
    if (hash_equals($calculatedHmac, $shopifyHmac)) {
        // Log the order data as it is valid
        Log::info('Shopify Order Webhook Received:', ['order_data' => json_decode($orderData, true)]);
        
        // You can also save the order data to the database or take other actions
        // Example: Order::create($orderData);

        return response()->json(['status' => 'success']);
    } else {
        Log::warning('Shopify Webhook HMAC Validation Failed');
        return response()->json(['status' => 'error', 'message' => 'Invalid HMAC signature'], 400);
    }
}

}
