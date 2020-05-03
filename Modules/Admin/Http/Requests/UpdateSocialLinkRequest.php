<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialLinkRequest extends FormRequest
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
            'fb_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            //'whatsapp_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'linkedin_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            'twitter_url' => 'required|regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/',
            
        ];
    }

    public function messages()
    {
        return [
            'fb_url.required' => 'This field is required',
            //'whatsapp_url.required' => 'This field is required',
            'linkedin_url.required' => 'This field is required',
            'twitter_url.required' => 'This field is required',
            'fb_url.regex' => 'Please Enter Valid url.',
            //'whatsapp_url.regex' => 'Please Enter Valid url.',
            'linkedin_url.regex' => 'Please Enter Valid url.',
            'twitter_url.regex' => 'Please Enter Valid url.',
        ];
    }
}
