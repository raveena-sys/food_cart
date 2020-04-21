<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StoreProductPrice extends Model
{

    use Notifiable;
    protected $table = 'store_product_price';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'product_id',
        'store_id',
        'custom_price',
        'status',
        
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
