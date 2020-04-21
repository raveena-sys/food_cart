<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Cms extends Model
{
    use Notifiable;
    protected $table = 'cms_pages';
    protected $fillable = ['page_title','page_content'];
}
