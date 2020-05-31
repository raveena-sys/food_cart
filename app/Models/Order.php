<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    	'store_id',
    	'order_type',
        'payment_method',        
    	'category_id',
    	'name',
    	'mobile_no',
    	'email',
    	'address',
    	'city',
    	'state',	
    	'zipcode',	
    	'additional_notes',	
    	'cart_item',	
        'extra_item',    
        'subtotal',    
        'total',    
        'delivery_ins',    
        'delivery_charge',    
        'discount',    
        'gst',    
    	'status',	
    	'created_at',
    	'updated_at'
   ];
    public $timestamps = true;



    public function store(){
        return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }
}
