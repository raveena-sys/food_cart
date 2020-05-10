<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DiscountCoupon extends Model
{
    use Notifiable;
    protected $table = 'discount_coupon';
    protected $fillable = [
        'id',
        'coupon_image',
        'coupon_code',
        'coupon_type',
        'coupon_amount',
        'expired_at',
        'status',
        'created_at',
        'updated_at'
    ];

    public function store(){
       return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }
}
