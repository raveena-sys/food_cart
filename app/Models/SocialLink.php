<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SocialLink extends Model
{
    use Notifiable;
    protected $fillable = ['id', 'store_id', 'fb_url', 'whatsapp_url', 'twitter_url', 'insta_url', 'created_at', 'updated_at'];
    
}
