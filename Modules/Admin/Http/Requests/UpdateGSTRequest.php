<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class UpdateGSTRequest extends FormRequest
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
            'gst_per' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'gst_per.required' => 'This field is required.',
            'gst_per.numeric' => 'Please enter valid number',
            
        ];
    }
}
