<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class PizzaSizeMaster extends Model
{
    use Notifiable;
    protected $table = 'pizza_size_master';
    protected $fillable = [
        'id',
        'name',
        'description',
        'price',
        'store_id',
        'size_master_id',
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
