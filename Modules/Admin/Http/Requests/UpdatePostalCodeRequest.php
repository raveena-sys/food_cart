<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class UpdatePostalCodeRequest extends FormRequest
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
            'zip_code.0' => "required|array|min:2",
            'price' => 'required|numeric',
            //'store_id' => 'required',
            //'description' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'zip_code.required' => 'This field is required.',
            'price.required' => 'This field is required.',
            'price.numeric' => 'Price must be valid number',
            //'store_id.required' => 'This field is required.',
            //'description.required' => 'Description field is required.',
        ];
    }
}
