<?php

/**
 * Description: this request is used only for admin reset password fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        return [
            'password' => 'required|min:6|max:12|regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            'confirm_password' => 'required|min:6|max:12|regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:password'
        ];
    }

     /*
     * Function for show validation messages.
     */

    public function messages() {
        return [
            'password.required' => 'Password field is required.',            
            'password.min' => 'Password must be at least 6 characters.',
            'password.max' => 'Password may not be greater than 12 characters.',            
            'confirm_password.required' => 'Confirm password field is required.',            
            'confirm_password.max' => 'Confirm Password may not be greater than 12 characters.',                        
            'confirm_password.same' => 'Confirm password and password must match.',  
            'confirm_password.regex' => 'Confirm password must include alphabet,number and special character.',                      
            'password.regex' => 'Password must include alphabet,number and special character.',                      
          
        ];
    }

}
