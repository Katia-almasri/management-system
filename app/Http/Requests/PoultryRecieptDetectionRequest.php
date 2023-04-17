<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoultryRecieptDetectionRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    
    public function rules()
    {
        return [
            "farm_id" => "required",
            "details.*.row_material_id" => "required",
            "details.*.detection_details.*.cage_weight" => "required|numeric|gt:0"

            
        ];

    }

    public function messages()
    {
        return [
           'farm_id.required'=>'يرجى اختيار المزرعة',
           'details.*.row_material_id.required'=>'يرجى إدخال نوع الطير',
           'details.*.detection_details.*.cage_weight.required'=>'يرجى إدخال وزن القفص',
           'details.*.detection_details.*.cage_weight.numeric'=>'يجب أن يكون وزن القفص رقم',
           'details.*.detection_details.*.cage_weight.gt'=>'يجب أن يكون وزن القفص رقماً أكبر تماماً من الصفر'

        ];
    }
}
