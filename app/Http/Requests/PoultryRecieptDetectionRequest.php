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

            "details.*.row_material_id" => "required",
            "details.*.detection_details.*.group_weight" => "required|numeric|gt:0",
            "details.*.detection_details.*.num_cages" => "required|numeric|gt:0"


        ];

    }

    public function messages()
    {
        return [
           'farm_id.required'=>'يرجى اختيار المزرعة',
           'details.*.row_material_id.required'=>'يرجى إدخال نوع الطير',
           'details.*.detection_details.*.group_weight.required'=>'يرجى إدخال وزن المجموعة',
           'details.*.detection_details.*.group_weight.numeric'=>'يجب أن يكون وزن المجموعة رقم',
           'details.*.detection_details.*.group_weight.gt'=>'يجب أن يكون وزن المجموعة رقماً أكبر تماماً من الصفر',

           'details.*.detection_details.*.num_cages.required'=>'يرجى إدخال عدد أقفاص المجموعة',
           'details.*.detection_details.*.num_cages.numeric'=>'يجب أن يكون عدد أقفاص المجموعة رقم',
           'details.*.detection_details.*.num_cages.gt'=>'يجب أن يكون عدد أقفاص المجموعة رقماً أكبر تماماً من الصفر'


        ];
    }
}
