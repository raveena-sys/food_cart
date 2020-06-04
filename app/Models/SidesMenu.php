<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SidesMenu extends Model
{
    use Notifiable;
    protected $table = 'sides_menu';
    protected $fillable = [
        'id',
        'sides_name',
        'description',
        'price',
        'store_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function store(){
       return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }
}
