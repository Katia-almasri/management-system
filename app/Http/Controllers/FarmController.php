<?php

namespace App\Http\Controllers;

use App\Models\AddOfferNotif;
use App\Models\RegisterFarmRequestNotif;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Farm;
use App\Models\PurchaseOffer;
use App\Models\DetailPurchaseOffer;
use App\Models\RowMaterial;
use App\systemServices\notificationServices;


use Auth;
use Carbon\Carbon;

class FarmController extends Controller
{
    use validationTrait;

    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new notificationServices();
    }
    public function displayFarms(Request $request)
    {
        $Farm = Farm::where('approved_at', '!=', null)
            ->get(array('id', 'name', 'owner', 'mobile_number', 'location'));
        return response()->json($Farm, 200);
    }

    public function displayPurchaseOffers(Request $request)
    {
        $PurchaseOffer = PurchaseOffer::with('detailpurchaseOrders', 'farm')->orderBy('id', 'DESC')->get();

        return response()->json($PurchaseOffer, 200);
    }

    //2. last 48 h
    public function displayPurchaseOffersLast48H(Request $request){

        $PurchaseOffer =  \DB::table('purchase_offers AS t1')
                ->select('t1.*', 'farms.username')
                ->join('farms', 'farms.id', '=', 't1.farm_id')
                ->leftJoin('sales_purchasing_requests AS t2','t2.offer_id','=','t1.id')
                ->whereNull('t2.offer_id')
                ->where('t1.created_at', '>=', Carbon::now()->subHours(48)->toDateTimeString())
                ->get();

        return response()->json($PurchaseOffer);
        
    }

    public function SoftDeleteFarm(Request $request, $FarmId)
    {
        Farm::find($FarmId)->delete();
        return response()->json(["status" => true, "message" => "تم حذف المزرعة بنجاح"]);
    }

    public function restoreFarm(Request $request, $FarmId)
    {
        Farm::onlyTrashed()->find($FarmId)->restore();
        return response()->json(["status" => true, "message" => "تم استرجاع المزرعة بنجاح"]);
    }

    public function displayFarmTrashed(Request $request)
    {
        $FarmTrashed = Farm::onlyTrashed()->get();
        return response()->json($FarmTrashed, 200);
    }


    public function registerFarm(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "username" => "required:min:3|max:255|unique:farms,username",
                "password" => "required|min:8|max:15",
                "location" => "required|max:255",
                "mobile_number" => "required",
                "owner" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $farm = new Farm();
        $farm->username = $request->username;
        $farm->name = $request->name;
        $farm->owner = $request->owner;
        $farm->password = bcrypt($request->password);
        $farm->location = $request->location;
        $farm->mobile_number = $request->mobile_number;
        $farm->save();

        //MAKE NEW NOTIFICATION RECORD
        $RegisterFarmRequestNotif = new RegisterFarmRequestNotif();
        $RegisterFarmRequestNotif->from = $farm->id;
        $RegisterFarmRequestNotif->is_read = 0;
        $RegisterFarmRequestNotif->owner = $farm->owner;
        $RegisterFarmRequestNotif->name = $farm->name;
        $RegisterFarmRequestNotif->save();

        //SEND NOTIFICATION REGISTER REQUEST TO SALES MANAGER USING PUSHER
        $data['from'] = $farm->id;
        $data['is_read'] = 0;
        $data['owner'] = $farm->owner;
        $data['name'] = $farm->name;
        $this->notificationService->registerFarmRequestNotification($data);
        ////////////////// SEND THE NOTIFICATION /////////////////////////
        return response()->json(["status" => true, "message" => "انتظار موافقة المدير"]);
    }

    public function LoginFarm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if (auth()->guard('farms')->attempt(['username' => request('username'), 'password' => request('password')])) {

            config(['auth.guards.api.provider' => 'farms']);

            $user = Farm::select('*')
                ->where([['id', '=', auth()->guard('farms')->user()->id]])->get();
            if ($user[0]->approved_at != Null) {
                $success = $user[0];
                $success['token'] = $user[0]->createToken('api-token', ['farms'])->accessToken;
                return response()->json($success, 200);
            } else {
                return response()->json(["status" => false, "message" => "انتظار موافقة المدير"]);
            }

        } else {
            return response()->json(['error' => ['UserName and Password are Wrong!!.']], 200);
        }
    }

    public function commandAcceptForFarm(Request $request, $farmId)
    {

        $findRequestFarm = Farm::where([['id', '=', $farmId]])
            ->update(array('approved_at' => Carbon::now()->toDateTimeString()));

        //UPDATE THE is_read IN REGISTER FARM REQUEST TO read
        $RegisterFarmRequestNotif = RegisterFarmRequestNotif::where('from', '=', $farmId)->update(['is_read' => 1]);
        return response()->json(["status" => true, "message" => "تمت الموافقة على حساب المزرعة"]);
    }

    public function displayFarmRegisterRequest(Request $request)
    {
        $requestRegister = Farm::where('approved_at', '=', Null)->get(array('id', 'name', 'owner', 'mobile_number', 'location'));
        return response()->json($requestRegister, 200);
    }

    public function addOffer(Request $request)
    {
        $offer = new PurchaseOffer();
        $offer->farm_id = $request->user()->id;
        $offer->save();
        //NOW THE DETAILS
        $totalAmount = 0;
        foreach ($request->details as $_detail) {
            $detailPurchaseOffer = new DetailPurchaseOffer();
            $detailPurchaseOffer->purchase_offers_id = $offer->id;
            $detailPurchaseOffer->amount = $_detail['amount'];
            $detailPurchaseOffer->type = $_detail['type'];
            $detailPurchaseOffer->save();

            $totalAmount += $_detail['amount'];
        }
        $findOffer = PurchaseOffer::find($offer->id)->update(['total_amount' => $totalAmount]);

        //MAKE NEW NOTIFICATION RECORD
        $AddOfferNotif = new AddOfferNotif();
        $AddOfferNotif->from = $request->user()->id;
        $AddOfferNotif->is_read = 0;
        $AddOfferNotif->total_amount = $totalAmount;
        $AddOfferNotif->save();

        //SEND NOTIFICATION ADD OFFER TO SALES MANAGER USING PUSHER
        $data['from'] = $request->user()->id;
        $data['is_read'] = 0;
        $data['total_amount'] = $totalAmount;
        $this->notificationService->addOfferNotification($data);
        ////////////////// SEND THE NOTIFICATION /////////////////////////

        return response()->json(["status" => true, "message" => "تم إضافة العرض بنجاح"]);
    }

    public function displayMyOffers(Request $request)
    {
        $displayOffer = PurchaseOffer::with('detailpurchaseOrders')->where('farm_id', $request->user()->id)->orderBy('id', 'DESC')->get();
        return response()->json($displayOffer, 200);
    }
    public function deleteOffer(Request $request, $offerId)
    {
        PurchaseOffer::find($offerId)->delete();
        DetailPurchaseOffer::where('purchase_offers_id', $offerId)->delete();
        return response()->json(["status" => true, "message" => "تم حذف العرض بنجاح"]);
    }

    public function displayRowMaterial(Request $request)
    {
        $rowMaterial = RowMaterial::get();
        return response()->json($rowMaterial, 200);
    }
    public function displayDetailOffer(Request $request, $offer_id){
        $offer_details = DetailPurchaseOffer::where('purchase_offers_id', $offer_id)->get();
        return response()->json($offer_details);
    }

}