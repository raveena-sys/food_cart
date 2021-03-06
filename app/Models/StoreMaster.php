<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class StoreMaster  extends Model
{

    use Notifiable;
    protected $table = 'store_master';
    protected $appends = ['time_zone'];
    protected $fillable = [
        'id',
        'name',
        'short_name',
        'address1',
        'address2',
        'city_id',
        'state_id',
        'country_id',
        'pincode',
        'email',
        'password',
        'phone_number',
        'phone_number_country',
        'phone_number_country_code',
        'mobile_number',
        'mobile_number_country',
        'mobile_number_country_code',
        'latitude',
        'longitude',
        'thumb_image',
        'image',
        'open_time',
        'close_time',
        'pickup_delivery',
        'delivery_charge',
        'free_del_upto',
        'description',
        'status',
        'state_id',
        'country_id',
        'city_id',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];

    public function getTimeZoneAttribute()
    {
        return  'Asia/kolkata';
    }

    public function state()
    {
        return $this->hasOne('App\Models\State', 'id', 'state_id');
    }
    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'country_id');
    }
    public function city()
    {
        return $this->hasOne('App\Models\Cities', 'id', 'city_id');
    }
    public function social()
    {
        return $this->hasOne('App\Models\SocialLink', 'store_id', 'id');
    }


}
