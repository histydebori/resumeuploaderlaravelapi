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
          
            
            $table->string('contact_email');
<<<<<<< Updated upstream
            $table->timestamp('created_ats')->nullable();
            $table->string('currency');
            $table->string('current_total_price', 8, 2);
            $table->string('current_total_tax', 8, 2);
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
            $table->string('total_discounts', 8, 2);
            $table->string('total_price', 8, 2);
            $table->string('total_shipping_price', 8, 2);
            $table->string('total_tax', 8, 2);
            $table->string('total_tip_received', 8, 2);
            $table->string('total_weight', 8, 2);
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
=======
           $table->timestamp('created_ats')->nullable();
            
            $table->string('name')->nullable();
            $table->string('order_number');
           
>>>>>>> Stashed changes

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shopify_orders');
    }
};
