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
        'phone_number',
        'status',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];



    // public function companyDetails()
    // {
    //     return $this->hasOne('App\Models\Company', 'user_id');
    // }

    // public function employeeDetails()
    // {
    //     return $this->hasOne('App\Models\Employee', 'user_id');
    // }

    // public function managerDetails()
    // {
    //     return $this->hasOne('App\Models\Manager', 'user_id');
    // }

    // public function userAvialbility()
    // {
    //     return $this->hasOne('App\Models\UserAvailability', 'user_id');
    // }

    // public function userAvialbilities()
    // {
    //     return $this->hasMany('App\Models\UserAvailability', 'user_id');
    // }
    // public function userRating()
    // {
    //     return $this->hasOne('App\Models\Reviews', 'to_id');
    // }
    // public function userJobBids()
    // {
    //     return $this->hasMany('App\Models\JobBid', 'user_id');
    // }
}
