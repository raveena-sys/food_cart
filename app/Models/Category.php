<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use Notifiable;
    protected $table = 'category';
    protected $fillable = [
        'id',
        'name',
        'description',
        'thumb_image',
        'store_id',
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
