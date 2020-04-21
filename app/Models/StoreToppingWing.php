<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StoreToppingWing extends Model
{
    use Notifiable;
    protected $table = 'store_topping_wing';
    protected $fillable = [
        'id',
        'store_id',
        'top_wing_id',
        'custom_price',
        'status',
        'created_at',
        'updated_at',
    ];
}
