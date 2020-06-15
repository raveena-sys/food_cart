<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule; 


class AddStoreMasterRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            //'description' => 'required',
            'short_name' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
            'pincode' => 'required',
            'email' => [
                'required',
                'check_email_format',
                'regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/',
                Rule::unique('users')->where(function ($query)  {
                    return $query->where('status', '!=', 'deleted');
                }
                ),
            ],
            'password' => 'required|min:8',
            'phone_number' => [
                'required',
                //'numeric',
                //'digits_between:9,13',
                Rule::unique('users')->where(function ($query)  {
                    return $query->where('status', '!=', 'deleted');
                    }
                ),
            ],
            'phone_number_country_code' => 'required',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',
            // 'image' => 'required',
        ];
   
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'name.required' => 'This field is required.',
            //'description.required' => 'Description field is required.',
            'short_name.required' => 'This field is required.',
            'address1.required' => 'This field is required.',
            'address2.required' => 'This field is required.',
            'city.required' => 'This field is required.',
            'state.required' => 'This field is required.',
            'country.required' => 'This field is required.',
            'pincode.required' => 'This field is required.',
            'email.check_email_format' => 'Please enter valid email Id.',
            'email.required' => 'Please enter email Id.',
            'email.email' => 'Please enter valid email Id.',
            'email.unique' => 'The email has already been taken.',

            //'email.check_user_deleted' => 'Email has already been taken.',
            'password.required' => 'This field is required',
            'password.min' => 'Please enter atleast 8 characters',

            'phone_number.required' => 'This field is required.',
            //'phone_number.numeric' => 'Please Enter phone number in digits',
            'phone_number.unique' => 'The phone number has already been taken.',
            'phone_number.digits_between' => 'Phone number must be between 9 and 13 digits.',
            'phone_number_country_code.required' => ' This field is required.',
            'image.required' => 'This field is required.',
            'image.size' => 'Profile image size should not greater than 2 MB.',


        ];
    }
}
