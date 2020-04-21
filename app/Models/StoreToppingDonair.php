<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StoreToppingDonair extends Model
{
    use Notifiable;
    protected $table = 'store_topping_donair';
    protected $fillable = [
        'id',
        'store_id',
        'top_donair_id',
        'custom_price',
        'status',
        'created_at',
        'updated_at',
    ];
}
