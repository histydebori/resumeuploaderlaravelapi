<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->id();
            $table->string('shopify_order_id');
            $table->string('admin_graphql_api_id');
            $table->boolean('buyer_accepts_marketing')->default(false);
            $table->string('cancel_reason')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->string('contact_email');
            $table->timestamp('created_ats')->nullable();
            $table->string('currency');
            $table->decimal('current_total_price', 8, 2);
            $table->decimal('current_total_tax', 8, 2);
            $table->string('customer_locale');
            $table->string('email');
            $table->string('financial_status');
            $table->string('fulfillment_status')->nullable();
            $table->string('name')->nullable();
            $table->string('order_number');
            $table->string('payment_gateway_names');
            $table->string('phone')->nullable();
            $table->string('presentment_currency');
            $table->timestamp('processed_at')->nullable();
            $table->string('source_name');
            $table->string('tags');
            $table->decimal('total_discounts', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->decimal('total_shipping_price', 8, 2);
            $table->decimal('total_tax', 8, 2);
            $table->decimal('total_tip_received', 8, 2);
            $table->decimal('total_weight', 8, 2);
            $table->timestamp('updated_ats')->nullable();

            // Add customer information (e.g., customer name, address)
            $table->string('customer_first_name');
            $table->string('customer_last_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            // Shipping address fields
            $table->string('shipping_first_name');
            $table->string('shipping_address1');
            $table->string('shipping_city');
            $table->string('shipping_zip');
            $table->string('shipping_province');
            $table->string('shipping_country');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shopify_orders');
    }
};