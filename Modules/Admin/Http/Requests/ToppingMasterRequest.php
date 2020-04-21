<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ].
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class ToppingMasterRequest extends FormRequest {

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
            'foodtype' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric|regex:/^[0-9]+(\.[0-9]{1,2})?$/',
            'thumb_img' => 'nullable|mimes:jpeg,jpg,png,gif',
            'image' => 'nullable|mimes:jpeg,jpg,png,gif',


        ];
    }

    public function messages() {
        return [
          'foodtype.required' => 'Please select food type.',
          'name.required' => 'please fill food name.',
          'price.required' => 'please fill food price.',
          'price.regex' => 'please enter valid number.',
          'thumb_img' => 'Profile image size should not greater than 2 MB.',
          'image' => 'Profile image size should not greater than 2 MB.',


        ];
    }

}
