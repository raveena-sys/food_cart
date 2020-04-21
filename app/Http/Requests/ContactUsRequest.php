<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactUsRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|check_email_format|regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/',
             'phone_number' => 'required|numeric|digits_between:9,13',
            //'phone_number' => 'required|numeric|digits_between:9,13|unique:users,phone_number',

            // 'image' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'first_name.required' => 'First Name field is required.',
            'last_name.required' => 'Last Name field is required.',
            'email.check_email_format' => 'Email is required.',
            'email.required' => 'Please enter email Id.',
            'email.email' => 'Please enter valid email Id.',
            //'email.check_user_deleted' => 'Email has already been taken.',
            'phone_number.required' => 'Phone number field is required.',
            'phone_number.numeric' => 'Please Enter phone number in digits',
            'phone_number.digits_between' => 'Phone number must be between 9 and 13 digits.',


        ];
    }
}
