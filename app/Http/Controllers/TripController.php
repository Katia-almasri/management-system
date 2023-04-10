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
use App\Models\Driver;

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
                $DetailTrip->selling_port_id = $findRequest->selling_port_id;
                $DetailTrip->farm_id = $findRequest->farm_id;
                $findTruck = Truck::find($request->truck_id)->update(array('state' => 'في الرحلة'));
                $findDriver = Driver::find($request->driver_id)->update(array('state' => 'في الرحلة'));
                $DetailTrip->save();



                return  response()->json(["status"=>true, "message"=>"تم اضافة تفاصيل الرحلة بنجاح"]);


    }

    public function displayTrip(Request $request){
        $SalesPurchasingRequset = Trip::with(['truck','driver','requset1'=>function ($query) {
            $query->with('sellingPort','farm');
        }])->get();
        return response()->json($SalesPurchasingRequset, 200);
    }

}




