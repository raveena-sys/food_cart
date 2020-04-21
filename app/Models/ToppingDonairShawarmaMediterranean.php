<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ToppingDonairShawarmaMediterranean extends Model
{
    use Notifiable;
    protected $table = 'topping_donair_shawarma_mediterranean';
    protected $fillable = [
        'id',
        'food_type',
        'name',
        'price',
        'store_id',
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
