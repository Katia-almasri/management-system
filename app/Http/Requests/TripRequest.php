<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripRequest extends FormRequest
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
            "truck_id" => "required",
            "driver_id" => "required"
        ];
    }

    public function messages()
    {
        return [
           'truck_id.required'=>'رقم الشاحنة مطلوب',
           'driver_id.required'=>'رقم السائق مطلوب'
        ];
    }
}
