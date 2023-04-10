<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SalesPurchasingRequest extends FormRequest
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
            "details.*.amount" => "required|numeric",
            "details.*.type" => "required",

        ];
    }

    public function messages()
    {
        return [
           'total_amount.required'=>'الكمية النهائية مطلوبة',
           'total_amount.numeric'=>'الكمية النهائية يجب أن تكون قيمة رقمية',
           'details.*.amount.required'=>'يجب تعبئة جميع كميات تفاصيل طلب الشراء أو المبيع',
           'details.*.amount.numeric'=>'يجب أن تكون جميع كميات تفاصيل طلب الشراء أو المبيع عبارة عن تفاصيل رقمية',
           'details.*.type.required'=>'يجب تعبئة جميع أنواع المنتجات في تفاصيل طلب الشراء أو المبيع',
           'farm_id.required_if'=>'هذا الطلب هو طلب شراء، لذلك يجب إدخال المزرعة المناسبة',
           'selling_port_id.required_if'=>'هذا الطلب هو طلب مبيع لذلك يجب إدخال منفذ البيع المناسب',
        ];
    }
}
