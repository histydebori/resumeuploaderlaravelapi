<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ShopifyWebhookController extends Controller
{
    /**
     * Handle Shopify Order Webhook
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handleOrderWebhook(Request $request)
    {
        // Shopify sends the data as JSON, decode it
        $orderData = $request->getContent();
        
        // Shopify includes the HMAC signature in the header for validation
        $shopifyHmac = $request->header('X-Shopify-Hmac-Sha256');
        $shopifySecret = env('1f25d5b780924fa3e540395d76c5b2f568235f30fbb92b4d2514dfc7ee053051');  // Make sure to add your secret in .env file

        // Verify the HMAC signature to ensure the request is from Shopify
        $calculatedHmac = base64_encode(hash_hmac('sha256', $orderData, $shopifySecret, true));

        // Check if the HMAC matches
        if (Hash::check($calculatedHmac, $shopifyHmac)) {
            // Process the order data (save to database, etc.)
            Log::info('Shopify Order Webhook Received:', $orderData);

            // Optionally, you can save the order data to the database
            // Example: Order::create($orderData);
            
            return response()->json(['status' => 'success']);
        } else {
            Log::warning('Shopify Webhook HMAC Validation Failed');
            return response()->json(['status' => 'error', 'message' => 'Invalid HMAC signature'], 400);
        }
    }
}
