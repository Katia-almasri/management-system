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
           "tot_weight"=>"required|numeric|gt:0",
           "empty_weight"=>"required|numeric|gt:0"
        ];
    }

    public function messages()
    {
        return [
           'tot_weight.required'=>'يرجى إدخال الوزن الكلي للشحنة',
           'tot_weight.numeric'=>'يجب أن يكون وزن الشحنة رقم',
           'tot_weight.gt'=>'يجب أن يكون وزن الشحنة رقماً أكبر تماماً من الصفر',

           'empty_weight.required'=>'يرجى إدخال الوزن الفارغ للشحنة',
           'empty_weight.numeric'=>'يجب أن يكون الوزن الفارغ رقم',
           'empty_weight.gt'=>'يجب أن يكون الوزن الفارغ رقماً أكبر تماماً من الصفر',

        ];
    }
}
