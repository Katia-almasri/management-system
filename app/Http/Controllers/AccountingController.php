<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\salesPurchasingRequset;
use Auth;

class AccountingController extends Controller
{
    use validationTrait;

    public function displaySalesRequests(Request $request){
        $findSalesRequest = salesPurchasingRequset::with('salesPurchasingRequsetDetail')
        ->where([['request_type', 0],['accept_by_sales',1],['accept_by_ceo',1]])->get();
        return response()->json($findSalesRequest, 200);
    }

    public function displayPurchacingRequests(Request $request){
        $findPurchacingRequest = salesPurchasingRequset::with('salesPurchasingRequsetDetail')
        ->where([['request_type', 1],['accept_by_sales',1],['accept_by_ceo',1]])->get();
        return response()->json($findPurchacingRequest, 200);
    }


}
