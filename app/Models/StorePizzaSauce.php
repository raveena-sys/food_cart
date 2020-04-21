<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StorePizzaSauce extends Model
{

    use Notifiable;
    protected $table = 'store_pizza_sauce';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'sauce_id',
        'store_id',
        'custom_price',
        'status',
        
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
