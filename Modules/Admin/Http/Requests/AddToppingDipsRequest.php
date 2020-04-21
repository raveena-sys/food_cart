<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class AddToppingDipsRequest extends FormRequest
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
            'food_type' => 'required',
            'price' => 'required|numeric|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'name.required' => 'Name field is required.',
            //'description.required' => 'description field is required.',

            'food_type.required' => 'This field is required.',
            'price.required' => 'Price field is required.',
            'price.numeric' => 'Please enter number in digits',
            'price.regex' => 'Please enter valid number.',


        ];
    }
}
