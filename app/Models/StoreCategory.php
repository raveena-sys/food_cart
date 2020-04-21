<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class StoreCategory extends Model
{

    use Notifiable;
    protected $table = 'store_category';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'cat_id',
        'store_id',
        'status'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
}
