<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StoreToppingPizza extends Model
{
    use Notifiable;
    protected $table = 'store_topping_pizza';
    protected $fillable = [
        'id',
        'store_id',
        'top_pizza_id',
        'custom_price',
        'status',
        'created_at',
        'updated_at',
    ];
}
