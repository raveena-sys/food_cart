<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
            'address' => 'required',
            'email' => 'required|check_email_format|regex:/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/',
            'phone_no' => 'required|numeric|digits_between:9,13|unique:users,phone_number',
            'city' => 'required',
            'province' => 'required',
            'province1' => 'required',
            'pay_method' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'name.required' => 'Name field is required.',
            'address.required' => 'Address field is required.',
            'email.check_email_format' => 'Please enter valid email Id.',
            'email.required' => 'Please enter email Id.',
            'email.email' => 'Please enter valid email Id.',
            //'email.check_user_deleted' => 'Email has already been taken.',
            'phone_number.required' => 'Phone number field is required.',
            'phone_number.numeric' => 'Please Enter phone number in digits',
            'phone_number.digits_between' => 'Phone number must be between 9 and 13 digits.',

            'city.required' => 'city field is required.',
            'province.required' => 'province field is required.',
            'province1.required' => 'province field is required.',
             'pay_method.required' => 'Payment method is required',




        ];
    }
}
