<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DriverRequest extends FormRequest
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
            'address' => 'required',
            'mobile_number' => 'required|numeric|digits_between:7,10',
        ];
    }

    public function messages()
    {
        return [
           'name.required'=>'اسم السائق مطلوب',
           'address.required'=>'عنوان السائق مطلوب',
           'mobile_number.required'=>'رقم موبايل السائق مطلوب',
           'mobile_number.numeric'=>'رقم موبايل السائق يجب أن يكون رقماً',
           'mobile_number.digits_between'=>'رقم موبايل السائق يجب أن يكون بين 7 و 10 رقماً',
        ];
    }
}
