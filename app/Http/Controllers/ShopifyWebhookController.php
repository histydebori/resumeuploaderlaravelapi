<?php

namespace App\Http\Controllers;

use App\Models\ShopifyOrder;
use App\Models\SpfyCustomer;
use App\Models\SpfyLineItem;
use App\Models\SpfyShippingAddre;
use App\Models\SpfyOrder;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class ShopifyWebhookController extends Controller
{
    //test


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

            // // Save the order data to the database
            $orderDataArray = json_decode($orderData, true);

            ShopifyOrder::create([
                'shopify_order_id' => $orderDataArray['id'],

                'contact_email' => $orderDataArray['contact_email'],
                'created_ats' => $orderDataArray['created_at'],

                'name' => $orderDataArray['name'],
                'order_number' => $orderDataArray['order_number'],

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


            // Log::error(message: 'first layer done');

        //     try{
        //     // Convert the JSON string to an array
        //     $dataArray = json_decode($orderData, true);

        //     // Check if 'orders' exist in the response
        //     if (isset($dataArray['orders']) && is_array($dataArray['orders'])) {

        //         foreach ($dataArray['orders'] as $orders) {
        //             // Prepare order data
        //             $order = [
        //                 'app_id' => $orders['app_id'] ?? null,
        //                 'browser_ip' => $orders['browser_ip'] ?? null,
        //                 'cancelled_at' => $orders['cancelled_at'] ?? null,
        //                 'cancel_reason' => $orders['cancel_reason'] ?? null,
        //                 'cart_token' => $orders['cart_token'] ?? null,
        //                 'checkout_id' => $orders['checkout_id'] ?? null,
        //                 'checkout_token' => $orders['checkout_token'] ?? null,
        //                 'closed_at' => $orders['closed_at'] ?? null,
        //                 'confirmation_number' => $orders['confirmation_number'] ?? null,
        //                 'confirmed' => $orders['confirmed'] ?? false,
        //                 'contact_email' => $orders['contact_email'] ?? null,
        //                 'created_at' => $orders['created_at'] ?? null,
        //                 'currency' => $orders['currency'] ?? null,
        //                 'current_subtotal_price' => $orders['current_subtotal_price'] ?? null,
        //                 'current_total_price' => $orders['current_total_price'] ?? null,
        //                 'current_total_tax' => $orders['current_total_tax'] ?? null,
        //                 'email' => $orders['email'] ?? null,
        //                 'financial_status' => $orders['financial_status'] ?? null,
        //                 'fulfillment_status' => $orders['fulfillment_status'] ?? null,
        //                 'id' => $orders['id'],
        //                 'name' => $orders['name'] ?? null,
        //                 'note' => $orders['note'] ?? null,
        //                 'number' => $orders['number'] ?? null,
        //                 'order_number' => $orders['order_number'] ?? null,
        //                 'order_status_url' => $orders['order_status_url'] ?? null,
        //                 'phone' => $orders['phone'] ?? null,
        //                 'processed_at' => $orders['processed_at'] ?? null,
        //                 'source_identifier' => $orders['source_identifier'] ?? null,
        //                 'source_name' => $orders['source_name'] ?? null,
        //                 'tags' => $orders['tags'] ?? null,
        //                 'token' => $orders['token'] ?? null,
        //                 'total_discounts' => $orders['total_discounts'] ?? null,
        //                 'total_line_items_price' => $orders['total_line_items_price'] ?? null,
        //                 'total_price' => $orders['total_price'] ?? null,
        //                 'total_tax' => $orders['total_tax'] ?? null,
        //                 'updated_at' => $orders['updated_at'] ?? null,
        //                 'user_id' => $orders['user_id'] ?? null
        //             ];

        //             // Save the order
        //             $orderCreation = SpfyOrder::create($order);

        //             // Prepare shipping address data
        //             $shipping = [
        //                 'address1' => $orders['shipping_address']['address1'] ?? null,
        //                 'address2' => $orders['shipping_address']['address2'] ?? null,
        //                 'city' => $orders['shipping_address']['city'] ?? null,
        //                 'country' => $orders['shipping_address']['country'] ?? null,
        //                 'country_code' => $orders['shipping_address']['country_code'] ?? null,
        //                 'first_name' => $orders['shipping_address']['first_name'] ?? null,
        //                 'last_name' => $orders['shipping_address']['last_name'] ?? null,
        //                 'latitude' => $orders['shipping_address']['latitude'] ?? null,
        //                 'longitude' => $orders['shipping_address']['longitude'] ?? null,
        //                 'name' => $orders['shipping_address']['name'] ?? null,
        //                 'phone' => $orders['shipping_address']['phone'] ?? null,
        //                 'province' => $orders['shipping_address']['province'] ?? null,
        //                 'province_code' => $orders['shipping_address']['province_code'] ?? null,
        //                 'zip' => $orders['shipping_address']['zip'] ?? null,
        //                 'spfy_order_id' => $orders['id']
        //             ];

        //             // Save shipping address
        //             $shippingCreation = SpfyShippingAddre::create($shipping);

        //             // Prepare customer data
        //             $customer = [
        //                 'email' => $orders['customer']['email'] ?? null,
        //                 'first_name' => $orders['customer']['first_name'] ?? null,
        //                 'last_name' => $orders['customer']['last_name'] ?? null,
        //                 'phone' => $orders['customer']['phone'] ?? null,
        //                 'updated_at' => $orders['customer']['updated_at'] ?? null,
        //                 'spfy_order_id' => $orders['id'],
        //                 'ids' => $orders['customer']['id'] ?? null
        //             ];

        //             // Save customer data
        //             $customerCreation = SpfyCustomer::create($customer);

        //             // Process line items
        //             $lineItems = $orders['line_items'] ?? [];

        //             foreach ($lineItems as $lines) {
        //                 $itemline = [
        //                     'fulfillable_quantity' => $lines['fulfillable_quantity'] ?? 0,
        //                     'fulfillment_status' => $lines['fulfillment_status'] ?? null,
        //                     'grams' => $lines['grams'] ?? 0,
        //                     'id' => $lines['id'],
        //                     'name' => $lines['name'] ?? null,
        //                     'price' => $lines['price'] ?? null,
        //                     'product_id' => $lines['product_id'] ?? null,
        //                     'quantity' => $lines['quantity'] ?? 1,
        //                     'requires_shipping' => $lines['requires_shipping'] ?? true,
        //                     'sku' => $lines['sku'] ?? null,
        //                     'taxable' => $lines['taxable'] ?? true,
        //                     'title' => $lines['title'] ?? null,
        //                     'total_discount' => $lines['total_discount'] ?? 0,
        //                     'variant_id' => $lines['variant_id'] ?? null,
        //                     'variant_title' => $lines['variant_title'] ?? null,
        //                     'vendor' => $lines['vendor'] ?? null,
        //                     'spfy_order_id' => $orders['id']
        //                 ];

        //                 // Save line item data
        //                 $lineCreation = SpfyLineItem::create($itemline);
        //             }

        //             // // Optionally, handle shipping lines or any other custom data here
        //             // $shippingLines = $orders['shipping_lines'] ?? [];

        //             // foreach ($shippingLines as $shippingLine) {
        //             //     // Prepare shipping line data
        //             //     $shippingLineData = [
        //             //         'title' => $shippingLine['title'] ?? null,
        //             //         'price' => $shippingLine['price'] ?? null,
        //             //         'discounted_price' => $shippingLine['discounted_price'] ?? null,
        //             //         'spfy_order_id' => $orders['id']
        //             //     ];

        //             //     // Save shipping line data (create or update as necessary)
        //             //     SpfyShippingLine::create($shippingLineData);
        //             // }
        //         }
        //     }
        // } catch (\Exception $e) {
        //     // Handle any errors or exceptions
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }

        //     Log::error('second layer done');



            return response()->json(['status' => 'success', 'message' => 'Order saved successfully']);
        } else {
            // If the HMAC does not match, return an error response
            Log::error('Invalid HMAC signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid HMAC'], 400);
        }
    }


    public function handleOrderWebhookstaging(Request $request)
    {
        // Shopify sends the data as raw JSON
        $orderData = $request->getContent();

        // Retrieve the HMAC signature from the request header
        $shopifyHmac = $request->header('X-Shopify-Hmac-Sha256');

        // Retrieve the Shopify secret key from the .env file
       // $shopifySecret = env('SHOPIFY_SECRET');  // This fetches the SHOPIFY_SECRET value from .env
       $shopifySecret = "1f25d5b780924fa3e540395d76c5b2f568235f30fbb92b4d2514dfc7ee053051";  // This fetches the SHOPIFY_SECRET value from .env

        // Ensure the secret key is present in the .env file
        if (empty($shopifySecret)) {
            Log::error('Shopify secret key is missing from .env');
            return response()->json(['status' => 'error', 'message' => 'Shopify secret key missing'], 500);
        }

        // Log the received HMAC for debugging purposes
        Log::info('staging Received HMAC:', ['shopify_hmac' => $shopifyHmac]);

        // Verify the HMAC signature to ensure the request is from Shopify
        $calculatedHmac = base64_encode(hash_hmac('sha256', $orderData, $shopifySecret, true));

        // Log the calculated HMAC for debugging purposes
        Log::info('staging Calculated HMAC:', ['calculated_hmac' => $calculatedHmac]);

        // Check if the calculated HMAC matches the received HMAC
        if (hash_equals($calculatedHmac, $shopifyHmac)) {
            // Log the incoming Shopify order data before saving it to the database
            Log::info('staging Shopify Order Webhook Received:', ['order_data' => json_decode($orderData, true)]);

            // // Save the order data to the database
            // $orderDataArray = json_decode($orderData, true);

            // ShopifyOrder::create([
            //     'shopify_order_id' => $orderDataArray['id'],

            //     'contact_email' => $orderDataArray['contact_email'],
            //     'created_ats' => $orderDataArray['created_at'],

            //     'name' => $orderDataArray['name'],
            //     'order_number' => $orderDataArray['order_number'],

            //     'customer_first_name' => $orderDataArray['customer']['first_name'],
            //     'customer_last_name' => $orderDataArray['customer']['last_name'],
            //     'customer_email' => $orderDataArray['customer']['email'],
            //     'customer_phone' => $orderDataArray['customer']['phone'],
            //     'shipping_first_name' => $orderDataArray['shipping_address']['first_name'],
            //     'shipping_address1' => $orderDataArray['shipping_address']['address1'],
            //     'shipping_city' => $orderDataArray['shipping_address']['city'],
            //     'shipping_zip' => $orderDataArray['shipping_address']['zip'],
            //     'shipping_province' => $orderDataArray['shipping_address']['province'],
            //     'shipping_country' => $orderDataArray['shipping_address']['country'],

            // ]);


            // Log::error(message: 'first layer done');

        //     try{
        //     // Convert the JSON string to an array
        //     $dataArray = json_decode($orderData, true);

        //     // Check if 'orders' exist in the response
        //     if (isset($dataArray['orders']) && is_array($dataArray['orders'])) {

        //         foreach ($dataArray['orders'] as $orders) {
        //             // Prepare order data
        //             $order = [
        //                 'app_id' => $orders['app_id'] ?? null,
        //                 'browser_ip' => $orders['browser_ip'] ?? null,
        //                 'cancelled_at' => $orders['cancelled_at'] ?? null,
        //                 'cancel_reason' => $orders['cancel_reason'] ?? null,
        //                 'cart_token' => $orders['cart_token'] ?? null,
        //                 'checkout_id' => $orders['checkout_id'] ?? null,
        //                 'checkout_token' => $orders['checkout_token'] ?? null,
        //                 'closed_at' => $orders['closed_at'] ?? null,
        //                 'confirmation_number' => $orders['confirmation_number'] ?? null,
        //                 'confirmed' => $orders['confirmed'] ?? false,
        //                 'contact_email' => $orders['contact_email'] ?? null,
        //                 'created_at' => $orders['created_at'] ?? null,
        //                 'currency' => $orders['currency'] ?? null,
        //                 'current_subtotal_price' => $orders['current_subtotal_price'] ?? null,
        //                 'current_total_price' => $orders['current_total_price'] ?? null,
        //                 'current_total_tax' => $orders['current_total_tax'] ?? null,
        //                 'email' => $orders['email'] ?? null,
        //                 'financial_status' => $orders['financial_status'] ?? null,
        //                 'fulfillment_status' => $orders['fulfillment_status'] ?? null,
        //                 'id' => $orders['id'],
        //                 'name' => $orders['name'] ?? null,
        //                 'note' => $orders['note'] ?? null,
        //                 'number' => $orders['number'] ?? null,
        //                 'order_number' => $orders['order_number'] ?? null,
        //                 'order_status_url' => $orders['order_status_url'] ?? null,
        //                 'phone' => $orders['phone'] ?? null,
        //                 'processed_at' => $orders['processed_at'] ?? null,
        //                 'source_identifier' => $orders['source_identifier'] ?? null,
        //                 'source_name' => $orders['source_name'] ?? null,
        //                 'tags' => $orders['tags'] ?? null,
        //                 'token' => $orders['token'] ?? null,
        //                 'total_discounts' => $orders['total_discounts'] ?? null,
        //                 'total_line_items_price' => $orders['total_line_items_price'] ?? null,
        //                 'total_price' => $orders['total_price'] ?? null,
        //                 'total_tax' => $orders['total_tax'] ?? null,
        //                 'updated_at' => $orders['updated_at'] ?? null,
        //                 'user_id' => $orders['user_id'] ?? null
        //             ];

        //             // Save the order
        //             $orderCreation = SpfyOrder::create($order);

        //             // Prepare shipping address data
        //             $shipping = [
        //                 'address1' => $orders['shipping_address']['address1'] ?? null,
        //                 'address2' => $orders['shipping_address']['address2'] ?? null,
        //                 'city' => $orders['shipping_address']['city'] ?? null,
        //                 'country' => $orders['shipping_address']['country'] ?? null,
        //                 'country_code' => $orders['shipping_address']['country_code'] ?? null,
        //                 'first_name' => $orders['shipping_address']['first_name'] ?? null,
        //                 'last_name' => $orders['shipping_address']['last_name'] ?? null,
        //                 'latitude' => $orders['shipping_address']['latitude'] ?? null,
        //                 'longitude' => $orders['shipping_address']['longitude'] ?? null,
        //                 'name' => $orders['shipping_address']['name'] ?? null,
        //                 'phone' => $orders['shipping_address']['phone'] ?? null,
        //                 'province' => $orders['shipping_address']['province'] ?? null,
        //                 'province_code' => $orders['shipping_address']['province_code'] ?? null,
        //                 'zip' => $orders['shipping_address']['zip'] ?? null,
        //                 'spfy_order_id' => $orders['id']
        //             ];

        //             // Save shipping address
        //             $shippingCreation = SpfyShippingAddre::create($shipping);

        //             // Prepare customer data
        //             $customer = [
        //                 'email' => $orders['customer']['email'] ?? null,
        //                 'first_name' => $orders['customer']['first_name'] ?? null,
        //                 'last_name' => $orders['customer']['last_name'] ?? null,
        //                 'phone' => $orders['customer']['phone'] ?? null,
        //                 'updated_at' => $orders['customer']['updated_at'] ?? null,
        //                 'spfy_order_id' => $orders['id'],
        //                 'ids' => $orders['customer']['id'] ?? null
        //             ];

        //             // Save customer data
        //             $customerCreation = SpfyCustomer::create($customer);

        //             // Process line items
        //             $lineItems = $orders['line_items'] ?? [];

        //             foreach ($lineItems as $lines) {
        //                 $itemline = [
        //                     'fulfillable_quantity' => $lines['fulfillable_quantity'] ?? 0,
        //                     'fulfillment_status' => $lines['fulfillment_status'] ?? null,
        //                     'grams' => $lines['grams'] ?? 0,
        //                     'id' => $lines['id'],
        //                     'name' => $lines['name'] ?? null,
        //                     'price' => $lines['price'] ?? null,
        //                     'product_id' => $lines['product_id'] ?? null,
        //                     'quantity' => $lines['quantity'] ?? 1,
        //                     'requires_shipping' => $lines['requires_shipping'] ?? true,
        //                     'sku' => $lines['sku'] ?? null,
        //                     'taxable' => $lines['taxable'] ?? true,
        //                     'title' => $lines['title'] ?? null,
        //                     'total_discount' => $lines['total_discount'] ?? 0,
        //                     'variant_id' => $lines['variant_id'] ?? null,
        //                     'variant_title' => $lines['variant_title'] ?? null,
        //                     'vendor' => $lines['vendor'] ?? null,
        //                     'spfy_order_id' => $orders['id']
        //                 ];

        //                 // Save line item data
        //                 $lineCreation = SpfyLineItem::create($itemline);
        //             }

        //             // // Optionally, handle shipping lines or any other custom data here
        //             // $shippingLines = $orders['shipping_lines'] ?? [];

        //             // foreach ($shippingLines as $shippingLine) {
        //             //     // Prepare shipping line data
        //             //     $shippingLineData = [
        //             //         'title' => $shippingLine['title'] ?? null,
        //             //         'price' => $shippingLine['price'] ?? null,
        //             //         'discounted_price' => $shippingLine['discounted_price'] ?? null,
        //             //         'spfy_order_id' => $orders['id']
        //             //     ];

        //             //     // Save shipping line data (create or update as necessary)
        //             //     SpfyShippingLine::create($shippingLineData);
        //             // }
        //         }
        //     }
        // } catch (\Exception $e) {
        //     // Handle any errors or exceptions
        //     return response()->json(['error' => $e->getMessage()], 500);
        // }

        //     Log::error('second layer done');



            return response()->json(['status' => 'success', 'message' => 'Order saved successfully']);
        } else {
            // If the HMAC does not match, return an error response
            Log::error('Invalid HMAC signature');
            return response()->json(['status' => 'error', 'message' => 'Invalid HMAC'], 400);
        }
    }

}
