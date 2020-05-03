<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StorePostalCode extends Model
{
    use Notifiable;
    protected $fillable = [
        'id',
        'store_id',
        'postal_code',
        'price',
    ];

}
