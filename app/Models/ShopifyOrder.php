<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopifyOrder extends Model
{
    use HasFactory;
    protected $fillable = [
        'shopify_order_id',
        
        'contact_email',
        'created_ats',
        
<<<<<<< Updated upstream
<<<<<<< Updated upstream
        'name',
        'order_number',
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
=======
     
        'name',
        'order_number',
       
      
    
>>>>>>> Stashed changes
=======
     
        'name',
        'order_number',
       
      
    
>>>>>>> Stashed changes
    ];

    protected $casts = [
        'created_ats' => 'datetime',
<<<<<<< Updated upstream
<<<<<<< Updated upstream
    
=======
       
      
>>>>>>> Stashed changes
=======
       
      
>>>>>>> Stashed changes
    ];
}
