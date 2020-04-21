<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class AddPizzaCheeseRequest extends FormRequest
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
            'size_master_id' => 'required',
            'price' => 'required|numeric|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
        ];
    }

    public function messages()
    {
        return [
            'size_master_id.required' => 'Pizza Size field is required.',
            'price.required' => 'Price field is required.',
            'price.numeric' => 'Please Enter number in digits',
            'price.regex' => 'Please enter valid number.',
        ];
    }
}
