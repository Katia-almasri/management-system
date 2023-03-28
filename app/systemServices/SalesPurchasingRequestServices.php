<?php
namespace App\systemServices;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\SalesPurchasingRequest;
use Auth;
use Illuminate\Http\Request;

class SalesPurchasingRequestServices{

    public function processSalesPurchasingRequestAmounts(SalesPurchasingRequest $request){
        $totalAmount = $request->total_amount;

        $detailsAmounts = 0;
        foreach($request->details as $_detail){
            $detailsAmounts += $_detail['amount'];
        }

        if($detailsAmounts!=$totalAmount)
            return  ["status"=>false, "message"=>"الكمية الكلية لا تساوي مجموع كميات تفاصيل الطلب"];
        return  ["status"=>true, "message"=>"المجموع صحيح"];
    }

    public function calculcateTotalAmount(SalesPurchasingRequest $request){
        $totalAmount = 0;
        foreach($request->details as $_detail){
            $totalAmount += $_detail['amount'];
        }
        return  ["status"=>true, "result"=>$totalAmount];
    }
}
