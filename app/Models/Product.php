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
        'size_master_price',
        'food_type',
        'name',
        'description',
        'thumb_image',
        'image',
        'status',
        'price',
        'quantity',
        'add_customisation',
        'special_cat',
        'custom_product',
        'section_name',
        'customise_required',
        'pizza_size',
        'pizza_sauce',
        'pizza_crust',
        'common_topping',

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
   
}
