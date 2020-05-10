<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
            'coupon_image' => 'required',
            'discount_type' => 'required',
            'coupon_amount' => 'required',
            'expired_at' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'coupon_image.required' => 'This field is required.',
            'discount_type.required' => 'This field is required.',
            'coupon_amount.required' => 'This field is required.',
            'expired_at.required' => 'This field is required.',

        ];
    }
}

