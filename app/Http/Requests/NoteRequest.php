<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
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
            "detail" => "required|max:255"
        ];
    }

    public function messages()
    {
        return [
           'detail.required'=>'تفاصيل الملاحظة مطلوب إدخالها',
           'detail.max'=>'يجب ألا يزيد محتوى الرسالة عن 255 محرفاً',
        ];
    }
}
