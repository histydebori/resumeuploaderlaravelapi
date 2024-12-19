<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfyShippingAddre extends Model
{
    use HasFactory;
    protected $fillable = [      
        'address1',
        'address2',
        'city',
        'country',
        'country_code',
        'first_name',
        'last_name',
        'latitude',
        'longitude',
        'name',
         'phone',
        'province',
        'province_code',
        'zip',
        'id',
        'spfy_order_id'        
       ];
    public $timestamps= false;  
   
    public function order()
    {
        return $this->belongsTo(SpfyOrder::class);
    }
}
