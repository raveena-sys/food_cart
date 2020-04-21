<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PizzaCrustMaster extends Model
{
    use Notifiable;
    protected $table = 'pizza_crust_master';
    protected $fillable = [
        'id',
        'store_id',
        'name',
        'price',
        'store_id',
        'description',
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
