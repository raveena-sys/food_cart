<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ContactUs  extends Model
{

    use Notifiable;
    protected $table = 'contact_us';

    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'email',
        'company_name',
        'phone_number',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];


}
