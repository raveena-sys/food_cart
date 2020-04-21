<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Product extends Model
{
    use Notifiable;
    protected $table = 'product';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'store_id',
        'sub_category_id',
        'size_master_id',
        'food_type',
        'name',
        'description',
        'thumb_image',
        'image',
        'status',
        'price',
        'quantity',
        'topping_from', // radio button: 'none','topping_pizza','topping_wing_flavour','topping_donair_shawarma_mediterranean','topping_dips'
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }

    public function store()
    {
        return $this->hasOne('App\Models\StoreMaster', 'id', 'store_id');
    }

    public function subCategory()
    {
        return $this->belongsTo('App\Models\SubCategory', 'sub_category_id');
    }

    public function sizeMaster()
    {
        return $this->belongsTo('App\Models\SizeMaster', 'size_master_id');
    }
   
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
