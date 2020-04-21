<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest {

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
            'current_password' => 'required|current_password_match',
            //'new_password' => 'required|min:6|max:12|regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/',
            //'confirm_password' => 'required|min:6|max:12|regex:/^(?=.*?[a-zA-Z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{6,}$/|same:new_password'
            'new_password' => 'required|min:6|max:12',
            'confirm_password' => 'required|min:6|max:12|same:new_password'
        ];
    }

    /*
     * Function for show validation messages.
     */

    public function messages() {
        return [
            'current_password.current_password_match' => 'Current password does not match',
            'current_password.required' => 'Current password field is required.',
            'new_password.required' => 'New password field is required.',
            'new_password.regex' => 'Password must include alphabet,number and special character.',
            'confirm_password.required' => 'Confirm password field is required.',
            'new_password.min' => 'New password must be at least 6 characters.',
            'new_password.max' => 'New password may not be greater than 12 characters.',
            'confirm_password.regex' => 'Confirm password must include alphabet,number and special character.',

        ];
    }

}
