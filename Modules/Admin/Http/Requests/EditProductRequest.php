<?php

/**
 * Description: this request is used only for cms fields validation related operations.
 * Author : [myUser ]
 * Date : february 2019.
 */

namespace Modules\Admin\Http\Requests;

use Illuminate\Support\Facades\Response;
use Illuminate\Foundation\Http\FormRequest;

class EditProductRequest extends FormRequest
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
            //'store_id' => 'required',
            //'size_master_id' => 'required',
            'sub_category_id' => 'required',
            'food_type' => 'required',
            'price' => 'required',
            'quantity' => 'required',
            'topping_from' => 'required',
            //'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|dimensions:width=696,height=270',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'oldimage' => 'required',
        ];
    }

    public function messages()
    {
        return [
            //'name.alpha' => 'Name field allow only string',
            'name.required' => 'Name field is required.',
            //'description.required' => 'Description field is required.',
            //'store_id.required' => 'This field is required.',
            //'size_master_id.required' => 'This field is required.',
            'food_type.required' => 'This field is required.',
            'price.required' => 'This field is required.',
            'quantity.required' => 'This field is required.',
            'topping_from.required' => 'This field is required.',
            'sub_category_id.required' => 'This field is required.',
            'oldimage.required' => 'This field is required.',
            'image.size' => 'Profile image size should not greater than 2 MB.',


        ];
    }
}
