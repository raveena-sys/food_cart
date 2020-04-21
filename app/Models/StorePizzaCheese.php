<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StorePizzaCheese extends Model
{

    use Notifiable;
    protected $table = 'store_pizza_cheese';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'cheese_id',
        'store_id',
        'custom_price',
        'status'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
