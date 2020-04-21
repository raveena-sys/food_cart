<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SizeMaster extends Model
{
    use Notifiable;
    protected $table = 'size_master';
    protected $fillable = [
        'id',
        'name',
        'short_name',
        'value',
        'store_id',
        'thumb_image',
        'image',
        'description',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',

    ];
}
