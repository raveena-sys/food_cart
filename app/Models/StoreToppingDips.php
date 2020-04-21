<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StoreToppingDips extends Model
{

    use Notifiable;
    protected $table = 'store_topping_dips';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'top_dip_id',
        'store_id',
        'custom_price',
        'status'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
