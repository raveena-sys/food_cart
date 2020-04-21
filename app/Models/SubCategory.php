<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class SubCategory extends Model
{
    use Notifiable;
    protected $table = 'sub_category';
    protected $fillable = [
        'id',
        'category_id',
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

    public function category()
    {
        return $this->hasOne('App\Models\Category', 'category_id', 'id');
    }
    public static function getConcatSubcategory($categoryid)
    {
        return SubCategory::where(['category_id' => $categoryid, 'status' => 'active'])->orderby('id')->pluck('id');       
    }
}
