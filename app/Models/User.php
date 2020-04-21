<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;
    protected $table = 'users';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'name',
        'email',
        'password',
        'verify_token',
        'is_visibility',
        'user_type',
        'user_role',
        'availability',
        'is_subscribed',
        'status',
        'last_login_time',
        'profile_image',
        'availability',
        'phone_number_country',
        'phone_number_country_code',
        'phone_number',
        'about_me',
        'referral_code',
        'referred_by',
        'remember_token',
        'store_id',
        'created_by',
        'updated_by'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }
    public function companyDetails()
    {
        return $this->hasOne('App\Models\Company', 'user_id');
    }

    public function employeeDetails()
    {
        return $this->hasOne('App\Models\Employee', 'user_id');
    }

    public function managerDetails()
    {
        return $this->hasOne('App\Models\Manager', 'user_id');
    }

    public function userAvialbility()
    {
        return $this->hasOne('App\Models\UserAvailability', 'user_id');
    }

    public function userAvialbilities()
    {
        return $this->hasMany('App\Models\UserAvailability', 'user_id');
    }
    public function userRating()
    {
        return $this->hasOne('App\Models\Reviews', 'to_id');
    }
    public function userJobBids()
    {
        return $this->hasMany('App\Models\JobBid', 'user_id');
    }
}
