<?php

namespace App\Http\Controllers;

use App\Models\ShopifyOrder;
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


// public function handleOrderWebhook(Request $request)
// {
//     // Shopify sends the data as raw JSON
//     $orderData = $request->getContent(); 

//     // Retrieve the HMAC signature from the request header
//     $shopifyHmac = $request->header('X-Shopify-Hmac-Sha256');

//     // Retrieve the Shopify secret key from the .env file
//     $shopifySecret = env('SHOPIFY_SECRET');  // This fetches the SHOPIFY_SECRET value from .env

//     // Ensure the secret key is present in the .env file
//     if (empty($shopifySecret)) {
//         Log::error('Shopify secret key is missing from .env');
//         return response()->json(['status' => 'error', 'message' => 'Shopify secret key missing'], 500);
//     }

//     // Log the received HMAC for debugging purposes
//     Log::info('Received HMAC:', ['shopify_hmac' => $shopifyHmac]);

//     // Verify the HMAC signature to ensure the request is from Shopify
//     $calculatedHmac = base64_encode(hash_hmac('sha256', $orderData, $shopifySecret, true));

//     // Log the calculated HMAC for debugging purposes
//     Log::info('Calculated HMAC:', ['calculated_hmac' => $calculatedHmac]);

//     // Check if the calculated HMAC matches the received HMAC
//     if (hash_equals($calculatedHmac, $shopifyHmac)) {
//         // Process the order data, as the HMAC matches
//         Log::info('Shopify Order Webhook Received:', ['order_data' => json_decode($orderData, true)]);
        
//         // You can also save the order data to the database or perform other actions
//         // Order::create(json_decode($orderData, true));

//         return response()->json(['status' => 'success']);
//     } else {
//         // Log the failed validation attempt
//         Log::warning('Shopify Webhook HMAC Validation Failed');
//         return response()->json(['status' => 'error', 'message' => 'Invalid HMAC signature'], 400);
//     }
// }
public function handleOrderWebhook(Request $request)
{
    // Shopify sends the data as raw JSON
    $orderData = $request->getContent();

    // Retrieve the HMAC signature from the request header
    $shopifyHmac = $request->header('X-Shopify-Hmac-Sha256');

    // Retrieve the Shopify secret key from the .env file
    $shopifySecret = env('SHOPIFY_SECRET');  // This fetches the SHOPIFY_SECRET value from .env

    // Ensure the secret key is present in the .env file
    if (empty($shopifySecret)) {
        Log::error('Shopify secret key is missing from .env');
        return response()->json(['status' => 'error', 'message' => 'Shopify secret key missing'], 500);
    }

    // Log the received HMAC for debugging purposes
    Log::info('Received HMAC:', ['shopify_hmac' => $shopifyHmac]);

    // Verify the HMAC signature to ensure the request is from Shopify
    $calculatedHmac = base64_encode(hash_hmac('sha256', $orderData, $shopifySecret, true));

    // Log the calculated HMAC for debugging purposes
    Log::info('Calculated HMAC:', ['calculated_hmac' => $calculatedHmac]);

    // Check if the calculated HMAC matches the received HMAC
    if (hash_equals($calculatedHmac, $shopifyHmac)) {
        // Log the incoming Shopify order data before saving it to the database
        Log::info('Shopify Order Webhook Received:', ['order_data' => json_decode($orderData, true)]);

        // Save the order data to the database
        $orderDataArray = json_decode($orderData, true);

        ShopifyOrder::create([
            'shopify_order_id' => $orderDataArray['id'],
            'admin_graphql_api_id' => $orderDataArray['admin_graphql_api_id'],
            'buyer_accepts_marketing' => $orderDataArray['buyer_accepts_marketing'],
            'cancel_reason' => $orderDataArray['cancel_reason'],
            'cancelled_at' => $orderDataArray['cancelled_at'],
            'contact_email' => $orderDataArray['contact_email'],
            'created_ats' => $orderDataArray['created_at'],
            'currency' => $orderDataArray['currency'],
            'current_total_price' => $orderDataArray['current_total_price'],
            'current_total_tax' => $orderDataArray['current_total_tax'],
            'customer_locale' => $orderDataArray['customer_locale'],
            'email' => $orderDataArray['email'],
            'financial_status' => $orderDataArray['financial_status'],
            'fulfillment_status' => $orderDataArray['fulfillment_status'],
            'name' => $orderDataArray['name'],
            'order_number' => $orderDataArray['order_number'],
            'payment_gateway_names' => implode(', ', $orderDataArray['payment_gateway_names']),
            'phone' => $orderDataArray['phone'],
            'presentment_currency' => $orderDataArray['presentment_currency'],
            'processed_at' => $orderDataArray['processed_at'],
            'source_name' => $orderDataArray['source_name'],
            'tags' => $orderDataArray['tags'],
            'total_discounts' => $orderDataArray['total_discounts'],
            'total_price' => $orderDataArray['total_price'],
            'total_shipping_price' => $orderDataArray['total_shipping_price'],
            'total_tax' => $orderDataArray['total_tax'],
            'total_tip_received' => $orderDataArray['total_tip_received'],
            'total_weight' => $orderDataArray['total_weight'],
            'updated_ats' => $orderDataArray['updated_at'],
            'customer_first_name' => $orderDataArray['customer']['first_name'],
            'customer_last_name' => $orderDataArray['customer']['last_name'],
            'customer_email' => $orderDataArray['customer']['email'],
            'customer_phone' => $orderDataArray['customer']['phone'],
            'shipping_first_name' => $orderDataArray['shipping_address']['first_name'],
            'shipping_address1' => $orderDataArray['shipping_address']['address1'],
            'shipping_city' => $orderDataArray['shipping_address']['city'],
            'shipping_zip' => $orderDataArray['shipping_address']['zip'],
            'shipping_province' => $orderDataArray['shipping_address']['province'],
            'shipping_country' => $orderDataArray['shipping_address']['country'],
        ]);

        return response()->json(['status' => 'success', 'message' => 'Order saved successfully']);
    } else {
        // If the HMAC does not match, return an error response
        Log::error('Invalid HMAC signature');
        return response()->json(['status' => 'error', 'message' => 'Invalid HMAC'], 400);
    }
}


}
