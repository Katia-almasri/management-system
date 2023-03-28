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
                $SalesPurchasingRequest->accept = 0;
                $SalesPurchasingRequest->command = 0;
                if($request->request_type==1){
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

    public function displaySalesPurchasingRequest(Request $request){
        $SalesPurchasingRequset = salesPurchasingRequset::get();
        return response()->json($SalesPurchasingRequset, 200);
    }


    public function displayDetailsSalesPurchasingRequest(Request $request, $RequestId){
        $salesPurchasingRequsetDetail = salesPurchasingRequsetDetail::where('requset_id',$RequestId)->get();
        $salesPurchasingRequset = salesPurchasingRequset::where('id',$RequestId)->update(['is_seen'=>1]);

        return response()->json($salesPurchasingRequsetDetail, 200);
    }

    public function commandForMechanismCoordinator(Request $request, $RequestId){
        $findRecuest = salesPurchasingRequset::where([['accept',1],['id' , '=' , $RequestId]])->update(['command' => 1]);
        return response()->json(["status"=>true, "message"=>"command for Mechanism Coordinator successfully"]);
    }

    //استعراض الطلبات من قبل منسق حركة الاليات بعد الامر
    public function displaySalesPurchasingRequestFromMachenism(Request $request){
        $SalesPurchasingRequset = salesPurchasingRequset::with('salesPurchasingRequsetDetail','farm','sellingPort')
        ->where([['command',1],['accept',1]])->get();
        return response()->json($SalesPurchasingRequset, 200);
    }

}




