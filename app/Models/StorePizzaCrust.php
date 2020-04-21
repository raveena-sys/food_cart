<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StorePizzaCrust extends Model
{

    use Notifiable;
    protected $table = 'store_pizza_crust';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'crust_id',
        'store_id',
        'custom_price',
        'status'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
