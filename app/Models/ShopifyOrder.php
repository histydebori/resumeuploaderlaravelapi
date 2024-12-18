<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'shopify_order_id',
        'admin_graphql_api_id',
        'buyer_accepts_marketing',
        'cancel_reason',
        'cancelled_at',
        'contact_email',
        'created_at',
        'currency',
        'current_total_price',
        'current_total_tax',
        'customer_locale',
        'email',
        'financial_status',
        'fulfillment_status',
        'name',
        'order_number',
        'payment_gateway_names',
        'phone',
        'presentment_currency',
        'processed_at',
        'source_name',
        'tags',
        'total_discounts',
        'total_price',
        'total_shipping_price',
        'total_tax',
        'total_tip_received',
        'total_weight',
        'updated_at',
        'customer_first_name',
        'customer_last_name',
        'customer_email',
        'customer_phone',
        'shipping_first_name',
        'shipping_address1',
        'shipping_city',
        'shipping_zip',
        'shipping_province',
        'shipping_country'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'processed_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
