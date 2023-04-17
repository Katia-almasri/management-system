<?php

namespace App\Http\Controllers;

use App\Models\AddOfferNotif;
use App\Models\AddSalesPurchasingNotif;
use App\Models\RegisterSellingPortRequestNotif;
use App\Models\RequestToCompanyNotif;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Http\Requests\SalesPurchasingRequest;
use App\systemServices\SalesPurchasingRequestServices;
use App\systemServices\purchaseOfferServices;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Manager;
use App\Models\PurchaseOffer;
use App\Models\RegisterFarmRequestNotif;
use App\systemServices\notificationServices;


use Auth;
use Illuminate\Support\Facades\DB;

class SalesPurchasingRequestController extends Controller
{
    use validationTrait;

    protected $SalesPurchasingRequestService;
    protected $purchaseOfferService;
    protected $notificationService;

    public function __construct()
    {
        $this->SalesPurchasingRequestService  = new SalesPurchasingRequestServices();
        $this->purchaseOfferService = new purchaseOfferServices();
        $this->notificationService = new notificationServices();
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

                //MAKE NEW NOTIFICATION RECORD       
                $AddSalesPurchasingNotif = new AddSalesPurchasingNotif();
                $AddSalesPurchasingNotif->is_read = 0;
                if($request->request_type == 1){
                    $AddSalesPurchasingNotif->type = 'طلب شراء من منفذ بيع';
                    $AddSalesPurchasingNotif->selling_port_id = $request->selling_port_id;
                }
                else{
                    $AddSalesPurchasingNotif->type = 'طلب مبيع من مزرعة';
                    $AddSalesPurchasingNotif->farm_id = $request->farm_id;
                }
                
                $AddSalesPurchasingNotif->total_amount = $totalAmount['result'];
                $AddSalesPurchasingNotif->save();
                
                //SEND NOTIFICATION ADD OFFER TO SALES MANAGER USING PUSHER
                $data['is_read'] = 0;
                $data['total_amount'] = $totalAmount['result'];
                if($request->request_type == 1){
                    $data['type'] = 'طلب شراء من منفذ بيع';
                    $data['selling_port_id'] = $request->selling_port_id;
                }
                else{
                    $data['type'] = 'طلب مبيع من مزرعة';
                    $data['farm_id'] = $request->farm_id;
                }
                
                $this->notificationService->addSalesPurchaseToCEONotif($data);
                ////////////////// SEND THE NOTIFICATION /////////////////////////

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
        $offerDetail = $this->purchaseOfferService->compareOfferDetailsToRequestDetails($request->details, $offerId);
        if($offerDetail['status']==false)
            return  response()->json(["status"=>false, "message"=>$offerDetail['message']]);
        
        //CALCULATE TOTAL AMOUNT OF OFFER
         $totalAmount = $this->SalesPurchasingRequestService->calculcateTotalAmount($request);

        $findOffer = PurchaseOffer::find($offerId);
        //SAVE THE NEW OFFER
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
        //UPDATE THE is_read IN ADD OFFER TO read
        $AddOfferNotif = AddOfferNotif::where('from', '=', $findOffer->farm_id)->update(['is_read'=> 1]);
        return  response()->json(["status"=>true, "message"=>"تم إضافة الطلب بنجاح"]);
    }

    public function getResgisterFarmRequestsNotifs(Request $request){
        $RegisterFarmRequestNotif = RegisterFarmRequestNotif::where('is_read', '=', 0)->get();
        $countRegisterFarmRequestNotif = $RegisterFarmRequestNotif->count();
        return response()->json(['RegisterFarmRequestNotif'=> $RegisterFarmRequestNotif,
                                 'countRegisterFarmRequestNotif'=> $countRegisterFarmRequestNotif]);
    }  
    
    
    public function getResgisterSellingPortRequestsNotifs(Request $request){
        $RegisterSellingPortRequestNotif = RegisterSellingPortRequestNotif::where('is_read', '=', 0)->get();
        $countRegisterSellingPortRequestNotif = $RegisterSellingPortRequestNotif->count();
        return response()->json(['RegisterSellingPortRequestNotif'=> $RegisterSellingPortRequestNotif,
                                 'countRegisterSellingPortRequestNotif'=> $countRegisterSellingPortRequestNotif]);
    } 
    
    public function getAddOffersNotifs(Request $request){
        $AddOfferNotif = AddOfferNotif::where('is_read', '=', 0)->get();
        $countAddOfferNotif = $AddOfferNotif->count();
        return response()->json(['AddOfferNotif'=> $AddOfferNotif,
                                 'countAddOfferNotif'=> $countAddOfferNotif]);
    } 

    public function getRequestToCompanyNotifs(Request $request){
        $RequestToCompanyNotif = RequestToCompanyNotif::where('is_read', '=', 0)->get();
        $countRequestToCompanyNotif = $RequestToCompanyNotif->count();
        return response()->json(['RequestToCompanyNotif'=> $RequestToCompanyNotif,
                                 'countRequestToCompanyNotif'=> $countRequestToCompanyNotif]);
    }

}




