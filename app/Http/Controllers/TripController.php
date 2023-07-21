<?php

namespace App\Http\Controllers;

use App\systemServices\notificationServices;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Http\Requests\TripRequest;
use Validator;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Driver;
use App\Models\Farm;
use App\Models\Governorate;
use App\Models\SellingPort;


use App\Models\Manager;
use Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    use validationTrait;

    protected $notificationService;

    public function __construct()
    {
        $this->notificationService = new notificationServices();
    }


    public function displayCommandSalesPurchasing(Request $request){
        $findCommand = salesPurchasingRequset::doesnthave('trips')->with('salesPurchasingRequsetDetail','farm','sellingPort')
        ->where('command',1)->orderby('id','desc')->get();
        return response()->json($findCommand, 200);
    }
    //اضافة تفاصيل رحلة
    public function AddDetailTrip(TripRequest $request, $requestId){
        $validator = Validator::make($request->all(), [
            'truck_id' => 'required',
            'driver_id' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        try {
            DB::beginTransaction();
            $findRequest = salesPurchasingRequset::find($requestId);

            if($findRequest->command ==1){
                $DetailTrip = new Trip();
                $DetailTrip->manager_id = $request->user()->id;;
                $DetailTrip->truck_id = $request->truck_id;
                $DetailTrip->driver_id = $request->driver_id;
                $DetailTrip->sales_purchasing_requsets_id = $requestId;
                $findRequest = salesPurchasingRequset::find($requestId);
                $DetailTrip->selling_port_id = $findRequest->selling_port_id;
                $DetailTrip->farm_id = $findRequest->farm_id;
                $findTruck = Truck::find($request->truck_id)->update(array('state' => 'في الرحلة'));
                $findDriver = Driver::find($request->driver_id)->update(array('state' => 'في الرحلة'));
                $DetailTrip->save();

                //send notification to sales manager
                $data = $this->notificationService->makeNotification(
                    'add-trip',
                    'App\\Events\\addTripNotification',
                    'تم إعطاء أمر بانطلاق رحلة ',
                    '',
                    $request->user()->id,
                    $DetailTrip->id.'تم انطلاق الرحلة ',
                    $DetailTrip->id,
                    '',
                    ''
                );


                $this->notificationService->addTripNotif($data);

                DB::commit();
                return  response()->json(["status"=>true, "message"=>"تم اضافة تفاصيل الرحلة بنجاح"]);
            }
            else
            return  response()->json(["status"=>false, "message"=>"لم يعطى الامر من قبل مدير المشتريات والمبيعات"]);

        } catch (\Throwable $ex) {
            DB::rollBack();
            return  response()->json(["status"=>false, "message"=>$ex->getMessage()]);
        }




    }
    //عرض الرحلات
    public function displayTrip(Request $request){
        $SalesPurchasingRequset = Trip::with(['truck','driver','requset1'=>function ($query) {
            $query->with('sellingPort','farm','salesPurchasingRequsetDetail');
        }])->orderBy('id', 'DESC')->get();
        return response()->json($SalesPurchasingRequset, 200);
    }

    public function displayTripInLibra(Request $request){
        $trips = Trip::with('driver','truck','requset1.farm','requset1.salesPurchasingRequsetDetail')
        ->where([['status','في الرحلة'],['farm_id','!=',null]])->orderBy('id', 'DESC')->get();
        return response()->json($trips, 200);
    }


    public function SoftDeleteTrip(Request $request, $TripId){
        Trip::find($TripId)->delete();
       return  response()->json(["status"=>true, "message"=>"تم حذف الرحلة بنجاح"]);
   }


   public function SuitableTruck(Request $request, $SalesId){
        $SalesPurchasingRequset =  salesPurchasingRequset::where('id',$SalesId)->pluck('total_amount');
        $truck = Truck::where('storage_capacity','>',$SalesPurchasingRequset)
        ->where('state','متاحة')->orderBy ('storage_capacity','ASC')->get();
        $SalesPurchasingRequset1 =  salesPurchasingRequset::where('id',$SalesId)->pluck('farm_id');

        if($SalesPurchasingRequset1[0] != null){
            $farmDis = Farm::pluck('governorate_id');
            $dis = Governorate::where('id',$farmDis)->pluck('distance')->first();
            // return response()->json($SalesPurchasingRequset1, 200);
        }
        $SalesPurchasingRequset2 =  salesPurchasingRequset::where('id',$SalesId)->pluck('selling_port_id');
        if($SalesPurchasingRequset2[0] != null){
            $sellingPortDis = SellingPort::pluck('governorate_id');
            $dis = Governorate::where('id',$sellingPortDis)->pluck('distance')->first();
            // return response()->json($SalesPurchasingRequset2, 200);
        }

        foreach($truck as $_truck){
            $FuelConsumption = $_truck->oil_consumption * $dis /100;
            $_truck->FuelConsumption = $FuelConsumption;
        }
        return response()->json($truck, 200);
}



}




