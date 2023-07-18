<?php

namespace App\Http\Controllers;

use App\Events\addStartCommandNotif;
use App\Models\AddOfferNotif;
use App\Models\AddSalesPurchasingNotif;
use App\Models\Farm;
use App\Models\PoultryReceiptDetection;
use App\Models\RegisterSellingPortRequestNotif;
use App\Models\RequestToCompanyNotif;
use App\Models\SellingPort;
use \Carbon\Carbon;
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
use App\Models\Notification;
use App\Models\Manager;
use App\Models\PurchaseOffer;
use App\Models\DetailPurchaseOffer;
use App\Models\RegisterFarmRequestNotif;
use App\Models\Command_sales;
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
        $this->SalesPurchasingRequestService = new SalesPurchasingRequestServices();
        $this->purchaseOfferService = new purchaseOfferServices();
        $this->notificationService = new notificationServices();
    }
    //اضافة طلب شراء أو مبيع
    public function AddRequsetSalesPurchasing(SalesPurchasingRequest $request)
    {
        try {
            DB::beginTransaction();
            $totalAmount = $this->SalesPurchasingRequestService->calculcateTotalAmount($request);
            $SalesPurchasingRequest = new salesPurchasingRequset();
            $SalesPurchasingRequest->purchasing_manager_id = $request->user()->id;
            $SalesPurchasingRequest->ceo_id = Manager::where('managing_level', 'ceo')->get()->last()->id;
            $SalesPurchasingRequest->total_amount = $totalAmount['result'];
            $SalesPurchasingRequest->request_type = $request->request_type; //purchasing from farm_id
            $SalesPurchasingRequest->accept_by_sales = 1;
            $SalesPurchasingRequest->command = 0;
            $requestType = '';
            if ($request->request_type == 1) {
                $SalesPurchasingRequest->selling_port_id = $request->selling_port_id;
                $requestType = 'طلب مبيع';
            } else if ($request->request_type == 0) {
                $SalesPurchasingRequest->farm_id = $request->farm_id;
                $requestType = 'طلب شراء';
            }


            $SalesPurchasingRequest->save();
            //NOW THE DETAILS
            foreach ($request->details as $_detail) {
                $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
                $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
                $salesPurchasingRequsetDetail->amount = $_detail['amount'];
                $salesPurchasingRequsetDetail->type = $_detail['type'];
                $salesPurchasingRequsetDetail->price = $_detail['price'];
                $salesPurchasingRequsetDetail->save();
            }


            $data = $this->notificationService->makeNotification(
                'accept-refuse-sales-purchase-notification',
                'App\\Events\\acceptRefuseSalesPurchaseNotification',
                ' إضافة ' . $requestType,
                '',
                $SalesPurchasingRequest->id,
                ' تم إضافة ' . $requestType,
                $SalesPurchasingRequest->total_amount,
                'من قبل مدير المشتريات والمبيعات',
                ''
            );

            $this->notificationService->addSalesPurchaseToCEONotif($data);
            DB::commit();
            return response()->json(["status" => true, "message" => "تم إضافة الطلب بنجاح"]);

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => true, "message" => $ex->getMessage()]);
        }

        ////////////////// SEND THE NOTIFICATION /////////////////////////


    }


    public function commandForMechanismCoordinator(Request $request, $RequestId)
    {
        $findRecuest = salesPurchasingRequset::where([['accept_by_ceo', '=', 1], ['accept_by_sales', '=', 1], ['id', '=', $RequestId]])
            ->update(['command' => 1]);
        $find = salesPurchasingRequset::find($RequestId);
        $data = $this->notificationService->makeNotification(
            'add-start-command-notification',
            'App\\Events\\addStartCommandNotif',
            'أمر جديد لمنسق حركة الآليات',
            'http://127.0.0.1:8000//sales-api//command-for-mechanism//2',
            $RequestId, //sales purchaseing table id
            $RequestId . ' تم إعطاء أمر جديد للشحنة', //details
            $find->total_amount,
            // weight
            0,
            //output_from not important here
            ''
        );
        $this->notificationService->addStartCommandNotif($data);
        ////////////////// SEND THE NOTIFICATION /////////////////////////
        return response()->json(["status" => true, "message" => "تم اعطاء الامر لمنسق حركة الاليات"]);
    }


    public function commandForSalesRequest(Request $request , $RequestId){
        $this->commandForMechanismCoordinator($request , $RequestId);
        $commandSales = new Command_sales();
        $commandSales->sales_request_id = $RequestId;
        $commandSales->done = 0;
        $commandSales->save();
        return response()->json(["status" => true, "message" => " تم اعطاء الامر لمنسق حركة الاليات ولمشرف المخازن"]);
    }


    public function displayCommandSalesRequest(Request $request ){
        $commandSales = Command_sales::with('sales_request.salesPurchasingRequsetDetail')->get();
        return response()->json($commandSales, 200);
    }
    //استعراض الطلبات من قبل منسق حركة الاليات بعد الامر
    public function displaySalesPurchasingRequestFromMachenism(Request $request)
    {
        $SalesPurchasingRequset = salesPurchasingRequset::with('salesPurchasingRequsetDetail', 'farm', 'sellingPort')
            ->where([['command', '=', 1], ['accept_by_ceo', '=', 1], ['is_seen_by_mechanism_coordinator', '=', 0]])->orderBy('created_at', 'DESC')->get();

        $updateIsSeenStatus = salesPurchasingRequset::with('salesPurchasingRequsetDetail', 'farm', 'sellingPort')
            ->where([['command', '=', 1], ['accept_by_ceo', '=', 1], ['is_seen_by_mechanism_coordinator', '=', 0]])->update(['is_seen_by_mechanism_coordinator' => 1]);
        return response()->json($SalesPurchasingRequset, 200);
    }
    //الموافقة على طلب من قبل المدير التنفذي
    public function acceptSalesPurchasingRequestFromCeo(Request $request, $RequestId)
    {
        $find = salesPurchasingRequset::find($RequestId);
        $find->ceo_id = $request->user()->id;
        $find->save();
        salesPurchasingRequset::where('id', $RequestId)->update(['accept_by_ceo' => 1]);
        $requestType = '';
        $from = '';
        $fromName = '';
        if ($find->request_type == 1) {
            //selling port
            $requestType = 'طلب مبيع';
            $fromName = 'منفذ البيع';
            $from = SellingPort::find($find->selling_port_id);
        }
        if ($find->request_type == 0) {
            //farm
            $requestType = 'طلب شراء';
            $fromName = 'المزرعة';
            $from = Farm::find($find->farm_id);

        }

        $data = $this->notificationService->makeNotification(
            'accept-refuse-sales-purchase-notification',
            'App\\Events\\acceptRefuseSalesPurchaseNotification',
            ' قبول  ' . $requestType,
            '',
            $find->id,
            ' تم قبول ' . $requestType . ' من قبل المدير التنفيذي ',
            $find->total_amount,
            $fromName,
            ''

        );

        $this->notificationService->acceptRefuseSalesPurchaseNotif($data);

        return response()->json(["status" => true, "message" => "تمت الموافقة على الطلب بنجاح"]);
    }
    //رفض طلب من قبل المدير التنفيذي مع امكانية ادخال سبب الرفض
    public function refuseSalesPurchasingRequestFromCeo(Request $request, $RequestId)
    {
        $findOrder = salesPurchasingRequset::where('id', $RequestId)
            ->update(array('reason_refuse_by_ceo' => $request->reason_refuse_by_ceo));
        $findRequestOrder = salesPurchasingRequset::where([['id', '=', $RequestId]])
            ->update(['accept_by_ceo' => 0]);

        $find = salesPurchasingRequset::find($RequestId);

        $requestType = '';
        $from = '';
        $fromName = '';
        if ($find->request_type == 1) {
            //selling port
            $requestType = 'طلب مبيع';
            $fromName = 'منفذ البيع';
            $from = SellingPort::find($find->selling_port_id);
        }
        if ($find->request_type == 0) {
            //farm
            $requestType = 'طلب شراء';
            $fromName = 'المزرعة';
            $from = Farm::find($find->farm_id);

        }

        $data = $this->notificationService->makeNotification(
            'accept-refuse-sales-purchase-notification',
            'App\\Events\\acceptRefuseSalesPurchaseNotification',
            ' رفض  ' . $requestType,
            '',
            $find->id,
            ' تم رفض ' . $requestType . ' من قبل المدير التنفيذي ',
            $find->total_amount,
            $fromName,
            $find->reason_refuse_by_ceo

        );
        $this->notificationService->acceptRefuseSalesPurchaseNotif($data);
        return response()->json(["status" => true, "message" => "تم رفض الطلبية "]);
    }

    public function displaySalesPurchasingRequestFromCeo(Request $request)
    {
        $displayRequests = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->where([['accept_by_sales', 1], ['accept_by_ceo', null]])->orderby('id', 'desc')->get();
        return response()->json($displayRequests, 200);
    }

    public function calculcateTotalAmount(Request $request)
    {
        $totalAmount = 0;
        foreach ($request->details as $_detail) {
            $totalAmount += $_detail['amount'];
        }
        return $totalAmount;
    }


    //تأكيد طلب من عروض المزارع
    public function requestFromOffer(Request $request, $offerId)
    {
        $offerDetail = $this->purchaseOfferService->compareOfferDetailsToRequestDetails($request->details, $offerId);
        if ($offerDetail['status'] == false)
            return response()->json(["status" => false, "message" => $offerDetail['message']]);

        //CALCULATE TOTAL AMOUNT OF OFFER
        $totalAmount = $this->calculcateTotalAmount($request);

        $findOffer = PurchaseOffer::find($offerId);
        $findOfferDet = DetailPurchaseOffer::where('purchase_offers_id', $offerId)->get()->first();
        //SAVE THE NEW OFFER
        $SalesPurchasingRequest = new salesPurchasingRequset();
        $SalesPurchasingRequest->purchasing_manager_id = $request->user()->id;
        $SalesPurchasingRequest->ceo_id = Manager::where('managing_level', 'ceo')->get()->last()->id;
        $SalesPurchasingRequest->farm_id = $findOffer->farm_id;
        $SalesPurchasingRequest->offer_id = $offerId;
        $SalesPurchasingRequest->accept_by_sales = 1;
        $SalesPurchasingRequest->total_amount = $totalAmount;
        $SalesPurchasingRequest->request_type = 0;
        $SalesPurchasingRequest->save();

        //NOW THE DETAILS
        foreach ($request->details as $_detail) {
            $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
            $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
            $salesPurchasingRequsetDetail->amount = $_detail['amount'];
            $salesPurchasingRequsetDetail->type = $_detail['type'];
            $salesPurchasingRequsetDetail->price = $findOfferDet->price;
            $salesPurchasingRequsetDetail->save();
        }
        //send notification to ceo
        $data = $this->notificationService->makeNotification(
            'add-request-from-offer-notification',
            'App\\Events\\addRequestFromOfferNotification',
            'تم تأكيد طلب شراء ',
            '',
            $offerId,
            $SalesPurchasingRequest->id,
            $totalAmount,
            $findOffer->farm->name,
            ''
        );
        $this->notificationService->addRequestFromOfferNotif($data);

        return response()->json(["status" => true, "message" => "تم إضافة الطلب بنجاح"]);
    }

    public function getResgisterFarmRequestsNotifs(Request $request)
    {
        $RegisterFarmRequestNotif = RegisterFarmRequestNotif::where('is_read', '=', 0)->get();
        $countRegisterFarmRequestNotif = $RegisterFarmRequestNotif->count();
        return response()->json([
            'RegisterFarmRequestNotif' => $RegisterFarmRequestNotif,
            'countRegisterFarmRequestNotif' => $countRegisterFarmRequestNotif
        ]);
    }


    public function getResgisterSellingPortRequestsNotifs(Request $request)
    {
        $RegisterSellingPortRequestNotif = RegisterSellingPortRequestNotif::where('is_read', '=', 0)->get();
        $countRegisterSellingPortRequestNotif = $RegisterSellingPortRequestNotif->count();
        return response()->json([
            'RegisterSellingPortRequestNotif' => $RegisterSellingPortRequestNotif,
            'countRegisterSellingPortRequestNotif' => $countRegisterSellingPortRequestNotif
        ]);

    }


    //عدد أوامر الانطلاق يراها منسق حركة الآليات
    public function countStartCommandsNotifs(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-start-command-notification'],
            ['is_seen', '=', 0]
        ])->get();
        $notificationsCount = $notifications->count();
        // $countStartCommandsNotif = salesPurchasingRequset::where([['command', '=', 1], ['is_seen_by_mechanism_coordinator','=', 0]])->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);
    }

    // يراها مدير المشتريات والمبيعات عدد الشحنات الواصلة والتي تم وزنها
    public function countPoultryRecieptDetectionsNotifs(Request $request)
    {
        $countPoultryRecieptDetectionsNotif = PoultryReceiptDetection::where([['is_seen_by_sales_manager', '=', 0], ['is_weighted_after_arrive', '=', 1]])->count();
        return response()->json(['countPoultryRecieptDetectionsNotif' => $countPoultryRecieptDetectionsNotif]);
    }

    public function getAddOffersNotifs(Request $request)
    {
        $AddOfferNotif = AddOfferNotif::where('is_read', '=', 0)->get();
        $countAddOfferNotif = $AddOfferNotif->count();
        return response()->json([
            'AddOfferNotif' => $AddOfferNotif,
            'countAddOfferNotif' => $countAddOfferNotif
        ]);
    }

    public function getRequestToCompanyNotifs(Request $request)
    {
        $RequestToCompanyNotif = RequestToCompanyNotif::where('is_read', '=', 0)->get();
        $countRequestToCompanyNotif = $RequestToCompanyNotif->count();
        return response()->json([
            'RequestToCompanyNotif' => $RequestToCompanyNotif,
            'countRequestToCompanyNotif' => $countRequestToCompanyNotif
        ]);
    }

    public function DailyReportSalesRequests(Request $request)
    {
        $t = Carbon::today()->format('Y-m-d H:i:s.u e');
        $daily = DB::table('sales_purchasing_requests')
            ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
            ->where('sales_purchasing_requests.request_type', '=', 0)
            ->select('type', DB::raw('SUM(amount) as amount'))
            ->whereDate('sales-purchasing-requset-details.created_at', $t)->groupBy('type')
            ->get();
        return response()->json($daily, 200);
    }

    public function MonthlyReportSalesRequests(Request $request)
    {
        $currentMonth = date('m');
        $Monthly = DB::table('sales_purchasing_requests')
            ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
            ->where('sales_purchasing_requests.request_type', '=', 0)
            ->select('type', DB::raw('SUM(amount) as amount'))
            ->whereMonth('sales-purchasing-requset-details.created_at', Carbon::now()->month)->groupBy('type')
            ->whereBetween('sales-purchasing-requset-details.created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->get();

        return response()->json($Monthly, 200);
    }

    public function yearlyReportSalesRequests(Request $request)
    {
        $currentyear = date('y');
        $yearly = DB::table('sales_purchasing_requests')
            ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
            ->where('sales_purchasing_requests.request_type', '=', 0)
            ->select('type', DB::raw('SUM(amount) as amount'))
            ->whereBetween('sales_purchasing_requests.created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])->groupBy('type')->get();

        return response()->json($yearly, 200);
    }

    public function DailyReportoffer(Request $request)
    {
        $t = Carbon::today()->format('Y-m-d H:i:s.u e');
        $dailyOffer = DB::table('purchase_offers')
            ->join('purchase_offers_detail', 'purchase_offers.id', '=', 'purchase_offers_detail.purchase_offers_id')
            ->select('type', DB::raw('SUM(amount) as amount'))
            ->whereDate('purchase_offers_detail.created_at', $t)->groupBy('type')
            ->get();
        return response()->json($dailyOffer, 200);
    }

    public function MonthlyReportOffer(Request $request)
    {
        $currentMonth = date('m');
        $MonthlyOffer = DB::table('purchase_offers')
            ->join('purchase_offers_detail', 'purchase_offers.id', '=', 'purchase_offers_detail.purchase_offers_id')
            ->select('type', DB::raw('SUM(amount) as amount'))
            ->whereMonth('purchase_offers_detail.created_at', Carbon::now()->month)->groupBy('type')
            ->whereBetween('purchase_offers_detail.created_at', [
                Carbon::now()->startOfYear(),
                Carbon::now()->endOfYear(),
            ])
            ->get();

        return response()->json($MonthlyOffer, 200);
    }

    public function yearlyReportOffer(Request $request)
    {
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

    public function displayNonAcceptByCEO(Request $request)
    {
        $requests = salesPurchasingRequset::with('sellingPort', 'farm', 'salesPurchasingRequsetDetail')
            ->where('accept_by_ceo', null)
            ->orWhere('accept_by_ceo', 0)->orderBy('id', 'DESC')->get();
        return response()->json($requests, 200);
    }

    public function displayAcceptByCEO(Request $request)
    {
        $requests = salesPurchasingRequset::with('sellingPort', 'farm', 'salesPurchasingRequsetDetail')
            ->where([['accept_by_ceo', '=', 1]])->orderBy('id', 'DESC')->get();
        return response()->json($requests, 200);
    }

    ///////////////// إضافة مزرعة من قبل مدير المشتريات////////////////
    public function addFarm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $newFarm = new Farm();
        $newFarm->name = $request->name;
        $newFarm->username = '';
        $newFarm->password = '';
        $newFarm->owner = $request->name;
        $newFarm->location = '';
        $newFarm->mobile_number = 0;
        $newFarm->added_by = 'sales';
        $newFarm->approved_at = Carbon::now();
        $newFarm->save();
        return response()->json(["status" => true, "message" => 'تم إضافة مزرعة جديدة', "notification" => "بحاجة إلى إكمال معلومات المزرعة"]);
    }

    public function editFarmInfo(Request $request, $farmId)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:farms,username',
            'name' => 'required',
            'owner' => 'required',
            'location' => 'required',
            'mobile_number' => 'required|numeric'

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $farm = Farm::find($farmId);
        if ($farm->added_by != "sales")
            return response()->json(["status" => false, "message" => "لا يمكن إكمال معلومات هذه المزرعة، لم تقم بإضافتها!"]);
        $farm->name = $request->name;
        $farm->username = $request->username;
        $farm->owner = $request->owner;
        $farm->location = $request->location;
        $farm->mobile_number = $request->mobile_number;
        if ($request->governorate_id != null)
            $farm->governorate_id = $request->governorate_id;
        $farm->save();

        return response()->json(["status" => true, "message" => 'تم تعديل بيانات المزرعة بنجاح']);
    }

    public function displayFarm(Request $request, $farmId)
    {
        return response()->json(Farm::find($farmId));
    }

    /////////////////////////// NOTIFICATION PART ////////////////////////////////
    public function displyAcceptedRefusedNotification(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'accept-refuse-sales-purchase-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);
    }

    public function displyAcceptedRefusedNotificationAndChangeState(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'accept-refuse-sales-purchase-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'accept-refuse-sales-purchase-notification'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

    public function displyCommandNotification(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-start-command-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);
    }

    public function displyCommandNotificationChangeState(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-start-command-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'add-start-command-notification'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

}
