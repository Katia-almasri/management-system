<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Manager;
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
        return response()->json($_oldManager);

    }

    ///////////////////// NOTIFICATION PART ///////////////////
    public function displayRequestFromOfferNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'add-request-from-offer-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayRequestFromOfferNotificationAndChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'add-request-from-offer-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'add-request-from-offer-notification'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

    public function displaySalesPurchasingRequestNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'accept-refuse-sales-purchase-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displaySalesPurchasingRequestNotificationAndChangeState(Request $request){
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


    /////////////**********dashboard *////////////////////////

    public function numberUsers(Request $request){
        $number = Manager::where('date_of_leave',null)->get()->count();
        return response()->json($number);
    }



}
