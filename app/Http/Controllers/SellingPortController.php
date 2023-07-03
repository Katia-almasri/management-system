<?php

namespace App\Http\Controllers;

use App\Models\RegisterSellingPortRequestNotif;
use App\Models\RequestToCompanyNotif;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\SellingPort;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use App\systemServices\notificationServices;
use App\Models\Manager;
use App\Models\outPut_Type_Production;
use App\Models\Rating;

use Auth;
use Carbon\Carbon;
use App\Http\Requests\SalesPurchasingRequest;
use App\systemServices\SalesPurchasingRequestServices;

class SellingPortController extends Controller
{
    use validationTrait;
    protected $SalesPurchasingRequestService;
    protected $notificationService;

    public function __construct()
    {
        $this->SalesPurchasingRequestService = new SalesPurchasingRequestServices();
        $this->notificationService = new notificationServices();
    }

    //تسجيل حساب منفذ بيع
    public function registerSellingPort(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "username" => "required:min:3|max:255|unique:selling_ports,username",
                "password" => "required|min:8|max:15",
                "location" => "required|max:255",
                "mobile_number" => "required",
                "name" => "required",
                "type" => "required",
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $sellingPort = new SellingPort();
        $sellingPort->username = $request->username;
        $sellingPort->name = $request->name;
        $sellingPort->type = $request->type;
        $sellingPort->owner = $request->owner;
        $sellingPort->password = encrypt($request->password);
        $sellingPort->location = $request->location;
        $sellingPort->mobile_number = $request->mobile_number;
        $sellingPort->save();

        //MAKE NEW NOTIFICATION RECORD
        $RegisterSellingPortRequestNotif = new RegisterSellingPortRequestNotif();
        $RegisterSellingPortRequestNotif->from = $sellingPort->id;
        $RegisterSellingPortRequestNotif->is_read = 0;
        $RegisterSellingPortRequestNotif->owner = $sellingPort->owner;
        $RegisterSellingPortRequestNotif->name = $sellingPort->name;
        $RegisterSellingPortRequestNotif->save();

        //SEND NOTIFICATION REGISTER REQUEST TO SALES MANAGER USING PUSHER
        $data['from'] = $sellingPort->id;
        $data['is_read'] = 0;
        $data['owner'] = $sellingPort->owner;
        $data['name'] = $sellingPort->name;
        $this->notificationService->registerSellingPortRequestNotification($data);
        ////////////////// SEND THE NOTIFICATION /////////////////////////
        return response()->json(["status" => true, "message" => "انتظار موافقة المدير"]);
    }

    //تسجيل دخول لمنفذ بيع
    public function LoginSellingPort(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $sellingPort = SellingPort::where('username', $request->username)->get()->first();
        if ($sellingPort != null && decrypt($sellingPort->password) == $request->password) {

            config(['auth.guards.api.provider' => 'sellingports']);

            // $user = SellingPort::select('*')
            // ->where([['id','=',auth()->guard('sellingports')->user()->id]])->get();
            Auth::guard('sellingports')->login($sellingPort);
            if ($sellingPort->approved_at != Null) {
                $success = $sellingPort;
                $success['token'] = $sellingPort->createToken('api-token', ['sellingports'])->accessToken;
                return response()->json($success, 200);
            } else {
                return response()->json(["status" => false, "message" => "انتظار موافقة المدير"]);
            }

        } else {
            return response()->json(['error' => ['UserName and Password are Wrong.']], 200);
        }
    }

    //عرض منافذ البيع
    public function displaySellingPort(Request $request)
    {
        $SellingPort = SellingPort::where('approved_at', '!=', null)
            ->get(array('id', 'name', 'type', 'owner', 'mobile_number', 'location'));
        return response()->json($SellingPort, 200);
    }

    //عرض طلبات منافذ البيع
    public function displaySellingOrder(Request $request)
    {
        $SellingOrder = salesPurchasingRequset::with('salesPurchasingRequsetDetail', 'sellingPort')
            ->where('farm_id', NULL)->orderBy('id', 'DESC')->get();
        return response()->json($SellingOrder, 200);
    }

    //حذف منفذ بيع
    public function SoftDeleteSellingPort(Request $request, $sellingPortId)
    {
        SellingPort::find($sellingPortId)->delete();
        return response()->json(["status" => true, "message" => "تم حذف منفذ البيع"]);
    }

    //استرجاع منفذ بيع
    public function restoreSellingPort(Request $request, $SellingId)
    {
        SellingPort::withTrashed()->find($SellingId)->restore();
        return response()->json(["status" => true, "message" => "تم استرجاع منفذ البيع المحذوف"]);
    }

    //عرض منافذ البيع المحذوفة
    public function SellingPortTrashed(Request $request)
    {
        $SellingPortTrashed = SellingPort::onlyTrashed()
            ->get(array('id', 'name', 'type', 'owner', 'mobile_number', 'location', 'deleted_at'));
        return response()->json($SellingPortTrashed, 200);
    }

    //عرض طلبات منفذي
    public function displayMySellingPortRequest(Request $request)
    {
        $SellingPortRequest = salesPurchasingRequset::with('salesPurchasingRequsetDetail')
            ->where('selling_port_id', $request->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($SellingPortRequest, 200);
    }

    //حذف طلب من طلباتي كمنفذ بيع
    // public function deleteSellingPortOrder(Request $request , $SellingPortOrderId){
    //     $findRequest = salesPurchasingRequset::find($SellingPortOrderId)->delete();
    //    return  response()->json(["status"=>true, "message"=>"تم حذف طلب بنجاح"]);
    // }
    public function calculcateTotalAmount(Request $request)
    {
        $totalAmount = 0;
        foreach ($request->details as $_detail) {
            $totalAmount += $_detail['amount'];
        }
        return $totalAmount;
    }
    //اضافة طلب كمنفذ بيع
    public function addRequestToCompany(Request $request)
    {

        $totalAmount = $this->calculcateTotalAmount($request);

        $SalesPurchasingRequest = new salesPurchasingRequset();
        $SalesPurchasingRequest->total_amount = $totalAmount;
        $SalesPurchasingRequest->request_type = 1;
        $SalesPurchasingRequest->selling_port_id = $request->user()->id;
        $SalesPurchasingRequest->save();
        //NOW THE DETAILS
        foreach ($request->details as $_detail) {
            $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
            $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
            $salesPurchasingRequsetDetail->amount = $_detail['amount'];
            $salesPurchasingRequsetDetail->type = $_detail['type'];
            $salesPurchasingRequsetDetail->save();
        }

        //MAKE NEW NOTIFICATION RECORD
        $RequestToCompanyNotif = new RequestToCompanyNotif();
        $RequestToCompanyNotif->from = $request->user()->id;
        $RequestToCompanyNotif->is_read = 0;
        $RequestToCompanyNotif->total_amount = $totalAmount;
        $RequestToCompanyNotif->save();

        //SEND NOTIFICATION REGISTER REQUEST TO SALES MANAGER USING PUSHER
        $data['from'] = $request->user()->id;
        $data['is_read'] = 0;
        $data['total_amount'] = $totalAmount;
        $this->notificationService->addRequestToCompany($data);
        ////////////////// SEND THE NOTIFICATION /////////////////////////

        return response()->json(["status" => true, "message" => "تم إضافة الطلب بنجاح"]);
    }



    public function displaySellingPortRegisterRequest(Request $request)
    {
        $requestRegister = sellingPort::where('approved_at', '=', Null)->get(array('id', 'name', 'type', 'owner', 'mobile_number', 'location'));
        return response()->json($requestRegister, 200);
    }

    public function commandAcceptForSellingPort(Request $request, $sellingPortId)
    {
        $findRequest = sellingPort::where([['id', '=', $sellingPortId]])
            ->update(array('approved_at' => Carbon::now()->toDateTimeString()));

        //UPDATE THE is_read IN REGISTER SELLNING PORT REQUEST TO read
        $RegisterFarmRequestNotif = RegisterSellingPortRequestNotif::where('from', '=', $sellingPortId)->update(['is_read' => 1]);
        return response()->json(["status" => true, "message" => "تمت الموافقة على حساب منفذ البيع بنجاح"]);
    }

    public function commandAcceptForSellingPortOrder(Request $request, $SellingPortOrderId)
    {
        $find = salesPurchasingRequset::find($SellingPortOrderId);
        $findRequestOrder = salesPurchasingRequset::where([['id', '=', $SellingPortOrderId]])
            ->update(array('reason_refuse' => Null));
        $find->purchasing_manager_id = $request->user()->id;
        $find->save();
        salesPurchasingRequset::where([['id', '=', $SellingPortOrderId]])
            ->update(['accept_by_sales' => 1]);

        //UPDATE THE is_read IN REQUEST OFFER  TO read
        $RequestToCompanyNotif = RequestToCompanyNotif::where('from', '=', $find->selling_port_id)->update(['is_read' => 1]);

        return response()->json(["status" => true, "message" => "تمت الموافقة على طلب الشراء من قبل مدير المشتريات وارساله إلى المدير التنفيذي"]);
    }


    public function refuseOrderDetail(Request $request, $SellingPortOrderId)
    {
        $validator = Validator::make($request->all(), [
            'reason_refuse' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        $findOrder = salesPurchasingRequset::where([['id', '=', $SellingPortOrderId]])
            ->update(array('reason_refuse' => $request->reason_refuse));
        $findRequestOrder = salesPurchasingRequset::where([['id', '=', $SellingPortOrderId]])
            ->update(['accept_by_sales' => 0]);

        //UPDATE THE is_read IN REQUEST OFFER  TO read

        $find = salesPurchasingRequset::find($SellingPortOrderId);
        $RequestToCompanyNotif = RequestToCompanyNotif::where('from', '=', $find->selling_port_id)->update(['is_read' => 1]);

        return response()->json(["status" => true, "message" => "تم رفض الطلبية وتعبئة سبب الرفض"]);
    }

    public function displayOutputTypes(Request $request)
    {
        $types = outPut_Type_Production::pluck('type');
        return response()->json($types, 200);
    }


    public function addRatingToRequest(Request $request, $RequestId)
    {
        $SalesPurchasingRequest = salesPurchasingRequset::find($RequestId)->id;
        $rating = new Rating;
        $rating->rate = $request->rate;
        $rating->request_sales_id = $RequestId;
        $rating->note = $request->note;
        $rating->save();

        return response()->json(["status" => true, "message" => "تم إضافة التقييم"]);
    }

        //////////////////////  users profile and editiona /////////////////////
        public function displayMyProfile(Request $request)
        {
            $user = SellingPort::find($request->user()->id);
            $myProfileData = [
                'username' => $user->username,
                'name' => $user->name,
                'type' => $user->type,
                'owner' => $user->owner,
                'location' => $user->location,
                'mobile_number' => $user->mobile_number,
                'password' =>  decrypt($user->password)
            ];
    
            return response()->json($myProfileData);
        }
    
        public function editMyProfile(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'type' => 'required',
                'owner' => 'required',
                'location' => 'required',
                'mobile_number' => 'required',
                'username' => 'required',
                'password' =>  'required'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['error' => $validator->errors()->all()]);
            }
            $user = SellingPort::find($request->user()->id);
         
            $user->update([
                'name' => $request->name,
                'type' => $request->type,
                'owner' => $request->owner,
                'location' => $request->location,
                'mobile_number' => $request->mobile_number,
                'username' => $request->username,
                'password' =>  encrypt($request->password)
            ]);
            return response()->json(["status"=>true, "message"=>"تم تعديل بياناتك بنجاح"]);
        }
    
    

}