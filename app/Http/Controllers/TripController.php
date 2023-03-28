<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Http\Requests\TripRequest;
use Validator;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use App\Models\Trip;
use App\Models\Truck;
use App\Models\Manager;
use Auth;
use Illuminate\Support\Facades\DB;

class TripController extends Controller
{
    use validationTrait;


    public function AddDetailTrip(TripRequest $request, $requestId){
       
                $DetailTrip = new Trip();
                $DetailTrip->manager_id = $request->user()->id;;
                $DetailTrip->truck_id = $request->truck_id;
                $DetailTrip->driver_id = $request->driver_id;
                $DetailTrip->sales_purchasing_requsets_id = $requestId;
                $findRequest = salesPurchasingRequset::find($requestId);
                if($findRequest->selling_port_id != null)
                    $DetailTrip->selling_port_id = $findRequest->selling_port_id;
                else
                    $DetailTrip->selling_port_id = 0;

                if($findRequest->farm_id != null)
                    $DetailTrip->farm_id = $findRequest->farm_id;
                else
                    $DetailTrip->farm_id = 0;

                $findTrunkState = Truck::find($request->truck_id);
                if($findTrunkState->state =='متاحة'){
                    $findTrunkState->state = DB::raw("CONCAT('في الرحلة')");
                    if($findRequest->command == 1 && $findRequest->accept == 1){
                        $findTrunkState->save();
                        $DetailTrip->save();
                    }
                    else{
                        return  response()->json(["status"=>false, "message"=>"لا توجد موافقة من قبل المدير التنفيذي ومدير المشتريات والمبيعات"]);
                    }


                return  response()->json(["status"=>true, "message"=>"Trip created successfully"]);
                }
                else
                return  response()->json(["status"=>false, "message"=>"الشاحنة غير متاحة"]);

    }

    public function displayTrip(Request $request){
        $SalesPurchasingRequset = Trip::with(['truck','driver','requset1'=>function ($query) {
            $query->with('sellingPort','farm');
        }])->get();
        return response()->json($SalesPurchasingRequset, 200);
    }

    public function SoftDeleteTrip(Request $request) {

    }
}




