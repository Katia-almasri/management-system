<?php

namespace App\Http\Controllers;
Use \Carbon\Carbon;
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

    protected $SalesPurchasingRequestService;

    public function __construct()
    {
        $this->SalesPurchasingRequestService  = new SalesPurchasingRequestServices();
    }
    //اضافة طلب شراء أو مبيع
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
    //الموافقة على طلب من قبل المدير التنفذي
    public function acceptSalesPurchasingRequestFromCeo(Request $request, $RequestId){
        $find = salesPurchasingRequset::find($RequestId);
        $find->ceo_id = $request->user()->id;
        $find->save();
        salesPurchasingRequset::where('id',$RequestId)->update(['accept_by_ceo' => 1]);
        return response()->json(["status"=>true, "message"=>"تمت الموافقة على الطلب بنجاح"]);
    }
    //رفض طلب من قبل المدير التنفيذي مع امكانية ادخال سبب الرفض
    public function refuseSalesPurchasingRequestFromCeo(Request $request, $RequestId){
        $findOrder = salesPurchasingRequset::where([['id' , '=' , $RequestId]])
        ->update(array('reason_refuse_by_ceo' => $request->reason_refuse_by_ceo));
        $findRequestOrder = salesPurchasingRequset::where([['id' , '=' , $RequestId]])
        ->update(['accept_by_ceo'=>0]);

        return response()->json(["status"=>true, "message"=>"تم رفض الطلبية "]);
    }

    public function displaySalesPurchasingRequestFromCeo(Request $request){
        $displayRequests = salesPurchasingRequset::with('salesPurchasingRequsetDetail')
        ->where('accept_by_sales',1)->get();
        return response()->json($displayRequests, 200);
    }
    //تأكيد طلب من عروض المزارع
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

    public function DailyReportSalesRequests(Request $request){
        $t = Carbon::today()->format('Y-m-d H:i:s.u e');
        $daily = DB::table('sales_purchasing_requests')
        ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
        ->where ('sales_purchasing_requests.request_type','=',0)
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereDate('sales-purchasing-requset-details.created_at', $t)->groupBy('type')
        ->get();
        return response()->json($daily, 200);
    }

    public function MonthlyReportSalesRequests(Request $request){
        $currentMonth = date('m');
        $Monthly = DB::table('sales_purchasing_requests')
        ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
        ->where ('sales_purchasing_requests.request_type','=',0)
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereMonth('sales-purchasing-requset-details.created_at',  Carbon::now()->month)->groupBy('type')
        ->whereBetween('sales-purchasing-requset-details.created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])
        ->get();

        return response()->json($Monthly, 200);
    }

    public function yearlyReportSalesRequests(Request $request){
        $currentyear = date('y');
        $yearly = DB::table('sales_purchasing_requests')
        ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
        ->where ('sales_purchasing_requests.request_type','=',0)
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereBetween('sales_purchasing_requests.created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->groupBy('type')->get();

        return response()->json($yearly, 200);
    }

    public function DailyReportoffer(Request $request){
        $t = Carbon::today()->format('Y-m-d H:i:s.u e');
        $dailyOffer = DB::table('purchase_offers')
        ->join('purchase_offers_detail', 'purchase_offers.id', '=', 'purchase_offers_detail.purchase_offers_id')
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereDate('purchase_offers_detail.created_at', $t)->groupBy('type')
        ->get();
        return response()->json($dailyOffer, 200);
    }

    public function MonthlyReportOffer(Request $request){
        $currentMonth = date('m');
        $MonthlyOffer = DB::table('purchase_offers')
        ->join('purchase_offers_detail', 'purchase_offers.id', '=', 'purchase_offers_detail.purchase_offers_id')
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereMonth('purchase_offers_detail.created_at',  Carbon::now()->month)->groupBy('type')
        ->whereBetween('purchase_offers_detail.created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])
        ->get();

        return response()->json($MonthlyOffer, 200);
    }

    public function yearlyReportOffer(Request $request){
        $currentyear = date('y');
        $yearly = DB::table('purchase_offers')
        ->join('purchase_offers_detail', 'purchase_offers.id', '=', 'purchase_offers_detail.purchase_offers_id')
        ->select('type', DB::raw('SUM(amount) as amount'))
        ->whereBetween('purchase_offers_detail.created_at', [
            Carbon::now()->startOfYear(),
            Carbon::now()->endOfYear(),
        ])->groupBy('type')->get();

        return response()->json($yearly, 200);
    }
}




