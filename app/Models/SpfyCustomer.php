<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpfyCustomer extends Model
{
    use HasFactory;
    protected $fillable = [      
        'created_at',
       
        'email',
       
        'first_name',

        'last_name',
        
        'phone',
        'updated_at',
        'spfy_order_id',
        'ids'
            
       ];
    public $timestamps= false;  
   
}
