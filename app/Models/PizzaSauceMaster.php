<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PizzaSauceMaster extends Model
{
    use Notifiable;
    protected $table = 'pizza_sauce_master';
    protected $fillable = [
        'id',
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
