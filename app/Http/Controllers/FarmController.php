<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Farm;
use App\Models\PurchaseOffer;
use Auth;
use Carbon\Carbon;

class FarmController extends Controller
{
    use validationTrait;

    public function displayFarms(Request $request){
        $Farm = Farm::get(array('id','name','owner','mobile_number','location'));
        return response()->json($Farm, 200);
    }

    public function displayPurchaseOffers(Request $request){
        $PurchaseOffer = PurchaseOffer::with('detailpurchaseOrders','farm')
        ->get();
        return response()->json($PurchaseOffer, 200);
    }

    public function SoftDeleteFarm(Request $request, $FarmId){
        Farm::find($FarmId)->delete();
       return  response()->json(["status"=>true, "message"=>"تم حذف المزرعة بنجاح"]);
   }

   public function restoreFarm(Request $request, $FarmId)
   {
        Farm::onlyTrashed()->find($FarmId)->restore();
       return  response()->json(["status"=>true, "message"=>"تم استرجاع المزرعة بنجاح"]);
   }

   public function displayFarmTrashed(Request $request)
   {
       $FarmTrashed = Farm::onlyTrashed()->get();
       return response()->json($FarmTrashed, 200);
   }


   public function registerFarm(Request $request){
       $validator = Validator::make($request->all(),
       [
           "username"=>"required:min:3|max:255",
           "password" => "required|min:8|max:15",
           "location"=>"required|max:255",
           "mobile_number"=>"required",
           "owner"=>"required"
       ]);
       if ($validator->fails()) {
           return response()->json(['status'=>false,
           'message'=>$validator->errors()->all()
       ]);
       }

       $farm = new Farm();
       $farm->username = $request->username;
       $farm->owner = $request->owner;
       $farm->password =bcrypt($request->password);
       $farm->location = $request->location;
       $farm->mobile_number = $request->mobile_number;
       $farm->save();
       return  response()->json(["status"=>true, "message"=>"انتظار موافقة المدير"]);
   }

   public function LoginFarm(Request $request) {
    $validator = Validator::make($request->all(), [
        'username' => 'required',
        'password' => 'required',
    ]);

    if($validator->fails()){
        return response()->json(['error' => $validator->errors()->all()]);
    }

    if(auth()->guard('farms')->attempt(['username' => request('username'), 'password' => request('password')])){

        config(['auth.guards.api.provider' => 'farms']);

        $user = Farm::select('*')
        ->where([['id','=',auth()->guard('farms')->user()->id]])->get();
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

public function commandAcceptForFarm(Request $request, $farmId){

    $findRequestFarm = Farm::where([['id' , '=' , $farmId]])
    ->update(array('approved_at' => Carbon::now()->toDateTimeString()));
    return response()->json(["status"=>true, "message"=>"تمت الموافقة على حساب المزرعة"]);
}

public function displayFarmRegisterRequest(Request $request){
    $requestRegister = Farm::where('approved_at','=',Null)->get(array('id','name','owner','mobile_number','location'));
    return response()->json($requestRegister, 200);
}


}
