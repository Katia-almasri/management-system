<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Http\Requests\SalesPurchasingRequest;
use App\systemServices\SalesPurchasingRequestServices;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Manager;
use App\Models\PurchaseOffer;


use Auth;
use Illuminate\Support\Facades\DB;

class SalesPurchasingRequestController extends Controller
{
    use validationTrait;

    protected SalesPurchasingRequestServices $SalesPurchasingRequestService;

    public function __construct()
    {
        $this->SalesPurchasingRequestService  = new SalesPurchasingRequestServices();
    }

    public function AddRequsetSalesPurchasing(SalesPurchasingRequest $request){

              $totalAmount = $this->SalesPurchasingRequestService->calculcateTotalAmount($request);

                $SalesPurchasingRequest = new salesPurchasingRequset();
                $SalesPurchasingRequest->purchasing_manager_id = $request->user()->id;
                $SalesPurchasingRequest->ceo_id = Manager::where('managing_level', 'ceo')->get()->last()->id;
                $SalesPurchasingRequest->total_amount = $totalAmount['result'];
                $SalesPurchasingRequest->request_type = $request->request_type; //purchasing from farm_id
                $SalesPurchasingRequest->accept_by_ceo = 0;
                $SalesPurchasingRequest->command = 0;
                if($request->request_type == 1){
                    $SalesPurchasingRequest->selling_port_id = $request->selling_port_id;
                }
                else if($request->request_type==0){
                    $SalesPurchasingRequest->farm_id = $request->farm_id;
                }

                $SalesPurchasingRequest->save();
                //NOW THE DETAILS
                foreach($request->details as $_detail){
                    $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
                    $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
                    $salesPurchasingRequsetDetail->amount = $_detail['amount'];
                    $salesPurchasingRequsetDetail->type = $_detail['type'];
                    $salesPurchasingRequsetDetail->save();
                }

            return  response()->json(["status"=>true, "message"=>"تم إضافة الطلب بنجاح"]);
    }


    public function commandForMechanismCoordinator(Request $request, $RequestId){
        $findRecuest = salesPurchasingRequset::where([['accept_by_ceo',1],['accept_by_sales',1],['id' , '=' , $RequestId]])
        ->update(['command' => 1]);
        return response()->json(["status"=>true, "message"=>"تم اعطاء الامر لمنسق حركة الاليات"]);
    }

    //استعراض الطلبات من قبل منسق حركة الاليات بعد الامر
    public function displaySalesPurchasingRequestFromMachenism(Request $request){
        $SalesPurchasingRequset = salesPurchasingRequset::with('salesPurchasingRequsetDetail','farm','sellingPort')
        ->where([['command',1],['accept_by_ceo',1]])->get();
        return response()->json($SalesPurchasingRequset, 200);
    }

    public function acceptSalesPurchasingRequestFromCeo(Request $request, $RequestId){
        $find = salesPurchasingRequset::find($RequestId);
        $find->ceo_id = $request->user()->id;
        $find->save();
        salesPurchasingRequset::where('id',$RequestId)->update(['accept_by_ceo' => 1]);
        return response()->json(["status"=>true, "message"=>"تمت الموافقة على الطلب بنجاح"]);
    }

    public function requestFromOffer(SalesPurchasingRequest $request , $offerId){

        $totalAmount = $this->SalesPurchasingRequestService->calculcateTotalAmount($request);

        $findOffer = PurchaseOffer::find($offerId);
        $SalesPurchasingRequest = new salesPurchasingRequset();
        $SalesPurchasingRequest->purchasing_manager_id = $request->user()->id;
        $SalesPurchasingRequest->ceo_id = Manager::where('managing_level', 'ceo')->get()->last()->id;
        $SalesPurchasingRequest->farm_id = $findOffer->farm_id;
        $SalesPurchasingRequest->offer_id = $offerId;
        $SalesPurchasingRequest->accept_by_sales = 1;
        $SalesPurchasingRequest->total_amount = $totalAmount['result'];
        $SalesPurchasingRequest->request_type = 0;
        $SalesPurchasingRequest->save();
                //NOW THE DETAILS
                foreach($request->details as $_detail){
                    $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
                    $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
                    $salesPurchasingRequsetDetail->amount = $_detail['amount'];
                    $salesPurchasingRequsetDetail->type = $_detail['type'];
                    $salesPurchasingRequsetDetail->save();
                }

            return  response()->json(["status"=>true, "message"=>"تم إضافة الطلب بنجاح"]);
    }

}




