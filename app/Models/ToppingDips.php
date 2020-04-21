<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ToppingDips extends Model
{
    use Notifiable;
    protected $table = 'topping_dips';
    protected $fillable = [
        'id',
        'food_type',
        'name',
        'store_id',
        'price',
        'description',
        'thumb_image',
        'image',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',

    ];
    public function store(){
       return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }
}
