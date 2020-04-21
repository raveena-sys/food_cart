<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class EditSizeMasterRequest extends FormRequest
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
            'short_name' => 'required',
            'value' => 'required|numeric',
            //'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'name.required' => 'Name field is required.',
            //'description.required' => 'Description field is required.',
            'value.required' => 'Price field is required.',
            'value.numeric' => 'Please Enter number in digits',

            'short_name.required' => 'Sort Name field is required.',
        ];
    }
}
