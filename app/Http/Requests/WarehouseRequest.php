<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WarehouseRequest extends FormRequest
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
            "type_id"=>"required",
            "stockpile"=>"numeric|required",
            "minimum"=>"numeric|required",
 
        ];
    }


    public function messages()
    {
        return [
          "stockpile.numeric"=>'المخزون الاحتياطي من المادة يجب أن يكون رقماً',
          "stockpile.required"=>'يرجى إدخال المخزون الاحتياطي',
          "minimum.numeric"=>'الحد الأدنى من المادة يجب أن يكون رقماً',
          "minimum.required"=>'يرجى إدخال الحد الأدنى لهذه المادة',
        ];
    }
}
