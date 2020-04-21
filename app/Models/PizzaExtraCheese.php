<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PizzaExtraCheese extends Model
{
    use Notifiable;
    protected $table = 'pizza_extra_cheese';
    protected $fillable = [
        'id',
        'pizza_size_master',
        'price',
        'store_id',
        'status',
        'created_at',
        'updated_at'
    ];

    public function store(){
       return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }
    public function pizzaSize(){
       return $this->hasOne('App\Models\PizzaSizeMaster', 'id', 'pizza_size_master');
    }
}
