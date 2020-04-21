<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class DrinkMaster extends Model
{
    use Notifiable;
    protected $table = 'drink_master';
    protected $fillable = [
        'id',
        'category_type',
        'size_master_id',
        'price',
        'name',
        'description',
        'thumb_image',
        'image',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',

    ];
}
