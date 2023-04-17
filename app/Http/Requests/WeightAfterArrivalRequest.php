<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WeightAfterArrivalRequest extends FormRequest
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
            "details.*.detection_details.*.cage_weight" => "required|numeric|gt:0"
        ];
    }

    public function messages()
    {
        return [
           'details.*.detection_details.*.cage_weight.required'=>'يرجى إدخال وزن القفص',
           'details.*.detection_details.*.cage_weight.numeric'=>'يجب أن يكون وزن القفص رقم',
           'details.*.detection_details.*.cage_weight.gt'=>'يجب أن يكون وزن القفص رقماً أكبر تماماً من الصفر'

        ];
    }
}
