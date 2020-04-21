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
