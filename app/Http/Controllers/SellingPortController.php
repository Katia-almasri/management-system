<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\SellingPort;
use App\Models\SellingOrder;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\SellingOrderDetail;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;

use App\Models\Manager  ;

use Auth;
use Carbon\Carbon;
use App\Http\Requests\SalesPurchasingRequest;
use App\systemServices\SalesPurchasingRequestServices;

class SellingPortController extends Controller
{
    use validationTrait;
    protected SalesPurchasingRequestServices $SalesPurchasingRequestService;

    public function __construct()
    {
        $this->SalesPurchasingRequestService  = new SalesPurchasingRequestServices();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),
        [
            "username"=>"required:min:3|max:255|unique:selling_ports,username",
            "password" => "required|min:8|max:15",
            "location"=>"required|max:255",
            "mobile_number"=>"required"
        ]);
        if ($validator->fails()) {
            return response()->json(['status'=>false,
            'message'=>$validator->errors()->all()
        ]);
        }

        $sellingPort = new SellingPort();
        $sellingPort->username = $request->username;
        $sellingPort->owner = $request->owner;
        $sellingPort->password =bcrypt($request->password);
        $sellingPort->location = $request->location;
        $sellingPort->mobile_number = $request->mobile_number;
        $sellingPort->save();
        return  response()->json(["status"=>true, "message"=>"انتظار موافقة المدير"]);
    }


    public function Login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('sellingports')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'sellingports']);

            $user = SellingPort::select('*')
            ->where([['id','=',auth()->guard('sellingports')->user()->id]])->get();
            if($user[0]->approved_at!=Null){
            $success =  $user[0];
            $success['token'] =  $user[0]->createToken('api-token')->accessToken;
            return response()->json($success, 200);
            }
            else{
                return  response()->json(["status"=>false, "message"=>"انتظار موافقة المدير"]);
            }

        }else{
            return response()->json(['error' => ['UserName and Password are Wrong.']], 200);
        }
    }

    public function displaySellingPort(Request $request){
        $SellingPort = SellingPort::get(array('id','name','type','owner','mobile_number','location'));
        return response()->json($SellingPort, 200);
    }

    public function displaySellingOrder(Request $request){
        $SellingOrder = SellingOrder::with('sellingOrderDetails','sellingPort')->get();
        return response()->json($SellingOrder, 200);
    }

    public function SoftDeleteSellingPort(Request $request, $SellingId){
        SellingPort::find($SellingId)->delete();
       return  response()->json(["status"=>true, "message"=>"تم حذف منفذ البيع"]);
   }

   public function restoreSellingPort(Request $request, $SellingId)
   {
        SellingPort::withTrashed()->find($SellingId)->restore();
       return  response()->json(["status"=>true, "message"=>"تم استرجاع منفذ البيع المحذوف"]);
   }

   public function SellingPortTrashed(Request $request)
   {
       $SellingPortTrashed = SellingPort::onlyTrashed()->get(array('id','name','type','owner','mobile_number','location','deleted_at'));
       return response()->json($SellingPortTrashed, 200);
   }

    public function displaySellingPortRequest(Request $request){
        $SellingPortRequest = SellingOrder::with('sellingOrderDetails')
        ->where('SellingPort_id',$request->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($SellingPortRequest, 200);
    }



    public function addRequestFromCompany(Request $request){

          $RequestFromCompany = new SellingOrder();
          $RequestFromCompany->sellingPort_id = $request->user()->id;
          $RequestFromCompany->accept = 0;
          $RequestFromCompany->save();
          //NOW THE DETAILS
          foreach($request->details as $_detail){
              $OrderDetails = new SellingOrderDetail();
              $OrderDetails->selling_order_id = $RequestFromCompany->id;
              $OrderDetails->amount = $_detail['amount'];
              $OrderDetails->type = $_detail['type'];
              $OrderDetails->save();
          }

      return  response()->json(["status"=>true, "message"=>"تم إضافة الطلب بنجاح"]);
}


public function displaySellingPortRegisterRequest(Request $request){
    $requestRegister = sellingPort::where('approved_at','=',Null)->get(array('id','name','type','owner','mobile_number','location'));
    return response()->json($requestRegister, 200);
}

public function commandAcceptForSellingPort(Request $request, $SellingId){
    $findRequest = sellingPort::where([['admin',0],['id' , '=' , $SellingId]])
    ->update(array('approved_at' => Carbon::now()->toDateTimeString()));
    return response()->json(["status"=>true, "message"=>"confirm request for selling port successfully"]);
}

public function commandAcceptForSellingPortOrder(Request $request, $SellingPortOrderId){
    $findRequestOrder = SellingOrder::where([['id' , '=' , $SellingPortOrderId]])
    ->update(['accept_by_sales_manager'=>1]);
    $findOrder = SellingOrder::where([['id' , '=' , $SellingPortOrderId]]);
    $findDetailOrder = SellingOrderDetail::where('selling_order_id',$SellingPortOrderId)->get();



    $SalesPurchasingRequest = new salesPurchasingRequset();
    $SalesPurchasingRequest->purchasing_manager_id = $request->user()->id;
    $SalesPurchasingRequest->ceo_id = Manager::where('managing_level', 'ceo')->get()->last()->id;
    //totalaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
    $SalesPurchasingRequest->total_amount = 11;
    $SalesPurchasingRequest->request_type = 0; //purchasing from farm_id
    $SalesPurchasingRequest->selling_port_id = $findOrder->pluck('sellingPort_id')->first();
    $SalesPurchasingRequest->farm_id = 3;
    $SalesPurchasingRequest->save();
    //NOW THE DETAILS
    foreach($findDetailOrder as $_detail){
        $salesPurchasingRequsetDetail = new salesPurchasingRequsetDetail();
        $salesPurchasingRequsetDetail->requset_id = $SalesPurchasingRequest->id;
        $salesPurchasingRequsetDetail->amount = $_detail['amount'];
        $salesPurchasingRequsetDetail->type = $_detail['type'];
        $salesPurchasingRequsetDetail->save();
        }
    return response()->json(["status"=>true, "message"=>"تمت الموافقة على طلب الشراء من قبل مدير المشتريات وارساله إلى المدير التنفيذي"]);
}

}



