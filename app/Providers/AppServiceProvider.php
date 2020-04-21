<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Validator::extend('check_email_format', function ($attribute, $value, $parameters, $validator) {
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return false;
            } else {
                return true;
            }
        });
         /**
         * this method using for custom check for current password is valid or not on web side
         * @param type $attribute,$value,$parameters,$validator
         * @return boolean
         */
        Validator::extend('current_password_match', function ($attribute, $value, $parameters, $validator) {
            return Hash::check($value, Auth::user()->password);

        });

        Validator::extend('remove_spaces', function($attribute, $value, $parameters, $validator) {
            if (trim($value) == '') {
                return false;
            }
            return true;
        });
        
        Validator::extend('check_user_deleted', function($attribute, $value, $parameters, $validator) {
            $user =  User::where('email',$value)->where('status','!=','deleted')->first();
            if (!empty($user)) {
                return false;
            }
            return true;
        });
    }
}
