<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Hash;
use DB;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Illuminate\Support\Facades\Storage;
use Validator;
use App\Models\Manager;
use App\Models\salesPurchasingRequset;
use Auth;

class CEOController extends Controller
{
    use validationTrait;

    public function CEOLogin(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('managers')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'managers']);

            $user = Manager::select('*')->find(auth()->guard('managers')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('api-token')->accessToken;

            return response()->json($success, 200);
        }else{
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
         return response()->json([
           'message' => 'logged out successfully'
         ]);
    }

    public function getManagingLevel(Request $request){
        $managing_levels = Manager::pluck('managing_level');
        return response()->json($managing_levels);
    }

    public function addUser(Request $request){

        //search
        $oldManager = MAnager::where('managing_level', $request->managing_level)->latest('id')->first();
        $oldManager->update(['date_of_leave'=>Carbon::now()]);
        $manager = new Manager();
        $manager->managing_level = $request->managing_level;
        $manager->first_name = $request->first_name;
        $manager->last_name = $request->last_name;
        $password = $request->first_name.'123456';
        $manager->password = encrypt($password);
        $manager->username = $request->username;
        $manager->date_of_hiring = Carbon::now();
        $manager->save();

        $manager->attachRole($request->managing_level);
        //discuss managing level in arabic
        $managing_name_arabic = '';
        if($request->managing_level=='Purchasing-and-Sales-manager')
            $managing_name_arabic = 'مدير مشتريات ومبيعات';
        if($request->managing_level=='ceo')
            $managing_name_arabic = 'مدير تنفيذي';
        if($request->managing_level=='Mechanism-Coordinator')
            $managing_name_arabic = 'منسق حركة آليات';
        if($request->managing_level=='Production_Manager')
            $managing_name_arabic = 'مدير إنتاج';
        if($request->managing_level=='libra-commander')
            $managing_name_arabic = 'آمر قبان';
        if($request->managing_level=='slaughter_supervisor')
            $managing_name_arabic = 'مشرف ذبح';
        if($request->managing_level=='cutting_supervisor')
            $managing_name_arabic = 'مشرف تقطيع';
        if($request->managing_level=='Manufacturing_Supervisor')
            $managing_name_arabic = 'مشرف تصنيع';
        if($request->managing_level=='warehouse_supervisor')
            $managing_name_arabic = 'مشرف مخازن';

        return response()->json([
            'message' =>' تم إضافة '.$managing_name_arabic.' جديد'
          ]);
    }

    public function displayUsers(Request $request){
        $users = Manager::get(['id','username','managing_level','first_name','last_name','created_at','date_of_leave']);
        return response()->json($users);
    }

    public function displayNumUsersGroupByRoles(Request $request){
        $usersByRoles = Manager::select('managing_level',  \DB::raw('count(*) as total'))->groupBy('managing_level')->
        orderBy('managing_level')->
        get();
        return response()->json($usersByRoles);
    }

    public function restorUser(Request $request, $userId){

        $user = Manager::find($userId);
        $oldManager = Manager::where('managing_level', $user->managing_level)->get();
        foreach($oldManager as $_oldManager){
            $_oldManager->update(['date_of_leave'=>Carbon::now()]);
        }
        $user->update(['date_of_leave'=>null]);
        return response()->json([
            'message' =>' تم استرجاع '.$user->first_name
          ]);

    }

    ///////////////////// NOTIFICATION PART ///////////////////
    public function displayRequestFromOfferNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayRequestFromOfferNotificationAndChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

    public function displaySalesPurchasingRequestNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displaySalesPurchasingRequestNotificationAndChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

    //daily report with read and change state
    public function displayDailtReportNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayDailtReportNotificationAndChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'ceo-channel'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }



    /////////////**********dashboard *////////////////////////

    public function numberUsers(Request $request){
        $number = Manager::where('date_of_leave',null)->get()->count();
        return response()->json($number);
    }

    public function numberOfSalesRequest(Request $request){
        $numberSales = salesPurchasingRequset::where('request_type',1)->get()->count();
        return response()->json($numberSales);
    }

    public function numberOfSalesRequestِApproved(Request $request){
        $numberSales = salesPurchasingRequset::where([['request_type',1],['accept_by_ceo',1]])->get()->count();
        return response()->json($numberSales);
    }

    public function numberOfPurchasRequest(Request $request){
        $numberOfPurchas = salesPurchasingRequset::where('request_type',0)->get()->count();
        return response()->json($numberOfPurchas);
    }

    public function numberOfPurchasRequestApproved(Request $request){
        $numberOfPurchas = salesPurchasingRequset::where([['request_type',0],['accept_by_ceo',1]])->get()->count();
        return response()->json($numberOfPurchas);
    }


    public function ChartOfAmountSales(Request $request){
        $AmountSales = salesPurchasingRequset::select(DB::raw("SUM(amount) as sum"), DB::raw("MONTHNAME(sales_purchasing_requests.created_at) as month_name"))
                    ->join('sales-purchasing-requset-details','sales-purchasing-requset-details.requset_id','=','sales_purchasing_requests.id')
                    ->whereYear('sales_purchasing_requests.created_at', date('Y'))
                    ->where([['request_type',1],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('sales_purchasing_requests.id','ASC')
                    ->pluck('sum', 'month_name');
        $labels = $AmountSales->keys();
        $data = $AmountSales->values();
        return response()->json([
        'labels' => $labels,
        'data' => $data,
        ]);
    }


    public function ChartOfAmountPurchase(Request $request){
        $AmountPurchase = salesPurchasingRequset::select(DB::raw("SUM(amount) as sum"), DB::raw("MONTHNAME(sales_purchasing_requests.created_at) as month_name"))
                    ->join('sales-purchasing-requset-details','sales-purchasing-requset-details.requset_id','=','sales_purchasing_requests.id')
                    ->whereYear('sales_purchasing_requests.created_at', date('Y'))
                    ->where([['request_type',0],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('sales_purchasing_requests.id','ASC')
                    ->pluck('sum', 'month_name');
        $labels = $AmountPurchase->keys();
        $data = $AmountPurchase->values();
        return response()->json([
        'labels' => $labels,
        'data' => $data,
        ]);
    }

    public function PurchasePriceforThisMonth(Request $request){
        $PurchasePriceforThisMonth = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
                    ->join('sales-purchasing-requset-details','sales-purchasing-requset-details.requset_id','=','sales_purchasing_requests.id')
                    ->whereMonth('sales_purchasing_requests.created_at', date('m'))
                    ->where([['request_type',0],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->pluck('sum');
        return response()->json($PurchasePriceforThisMonth, 200);
    }

    public function SalesPriceforThisMonth(Request $request){
        $SalesPriceforThisMonth = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
                    ->join('sales-purchasing-requset-details','sales-purchasing-requset-details.requset_id','=','sales_purchasing_requests.id')
                    ->whereMonth('sales_purchasing_requests.created_at', date('m'))
                    ->where([['request_type',1],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->pluck('sum');
        return response()->json($SalesPriceforThisMonth, 200);
    }


    /////////////////// DAILY CEO REPORT ///////////////////////
    public function readDailyCEOReport(Request $request){
        // $filename = 'daily_ceo_report_' . date('Y_m_d') . '.txt';
        $filename = 'daily_ceo_report_2023_07_30.txt';
        if (Storage::exists($filename)) {

            $report = Storage::get($filename);
            $data = json_decode($report, true);
            return response()->json(["status"=>true, "data"=>$data]);

        }
        return response()->json(["status" => false, "data" => null, "message" => "لم يتم توليد التقرير لهذا اليوم بعد"]);
    }


}
