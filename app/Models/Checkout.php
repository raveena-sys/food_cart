<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Checkout  extends Model
{

    use Notifiable;
    protected $table = 'checkout';

    protected $fillable = [
        'id',
        'name',
        'address',
        'email',
        'phone_no',
        'city',
        'province',
        'province1',
        'created_at',
        'updated_at'

    ];


}
