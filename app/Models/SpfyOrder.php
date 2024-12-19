<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfyOrder extends Model
{
    use HasFactory;
    protected $fillable = [      
        'app_id',
'browser_ip',
'cancelled_at',
'cancel_reason',
'cart_token',
'checkout_id',
'checkout_token',
'closed_at',
'confirmation_number',
'confirmed',
'contact_email',
'created_at',
'currency',
'current_subtotal_price',
'current_total_price',
'current_total_tax',
'email',
'financial_status',
'fulfillment_status',
'id',
'name',
'note',
'number',
'order_number',
'order_status_url',
'phone',
'processed_at',
'source_identifier',
'source_name',
'tags',
'token',
'total_discounts',
'total_line_items_price',
'total_price',
'total_tax',
'updated_at',
'user_id'
       ];
    public $timestamps= false;  

    public function ship()
    {
        return $this->hasOne(SpfyShippingAddre::class);
    }
    
    public function cust()
    {
        return $this->hasone(SpfyCustomer::class);
    }
    
    public function line()
    {
        return $this->hasMany(SpfyLineItem::class);
    }
}
