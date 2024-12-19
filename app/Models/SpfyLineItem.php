<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfyLineItem extends Model
{
    use HasFactory;

    protected $fillable = [      
        'fulfillable_quantity',
        'fulfillment_status',
        'grams',
        'id',
        'name',
        'price',
        'product_id',
        'quantity',
        'requires_shipping',
        'sku',
        'taxable',
        'title',
        'total_discount',
        'variant_id',
        'variant_title',
        'vendor',
        'spfy_order_id'
        
       ];
    public $timestamps= false;  


}
