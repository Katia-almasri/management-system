<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\TruckRequest;
use App\Http\Requests\UpdateTruckRequest;
use App\Traits\validationTrait;
use App\Models\Truck;
use Validator;
use Auth;

class TruckContoller extends Controller
{
    use validationTrait;

    public function AddTruck(TruckRequest $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'model' => 'required',
            'storage_capacity' => 'required',
            'truck_number' => 'required|unique:trucks,truck_number',
            'governorate_name' => 'required'
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

            $truck = new Truck();
            $truck->mashenism_coordinator_id = $request->user()->id;
            $truck->name = $request->name;
            $truck->model = $request->model;
            $truck->truck_number = $request->truck_number;
            $truck->governorate_name = $request->governorate_name;
            $truck->storage_capacity = $request->storage_capacity;
            $truck->state = 'متاحة';
            $truck->save();
        return  response()->json(["status"=>true, "message"=>"تم اضافة الشاحنة بنجاح"]);
    }

    public function displayTruck(Request $request){
        $displayTrucks = Truck::get();
        return response()->json($displayTrucks, 200);
    }

    public function UpdateTruckState(UpdateTruckRequest $request,$TruckId){

        $TrunkState = Truck::find($TruckId);
        $TrunkState->state = $request->state;
        $TrunkState->save();
        return  response()->json(["status"=>true, "message"=>"تم تعديل حالة الشاحنة بنجاح"]);
    }

    public function SoftDeleteTruck(Request $request, $TruckId){
         Truck::find($TruckId)->delete();
        return  response()->json(["status"=>true, "message"=>"تم حذف الشاحنة بنجاح"]);
    }

    public function restoreTruck(Request $request, $TruckId)
    {
        Truck::withTrashed()->find($TruckId)->restore();

        return  response()->json(["status"=>true, "message"=>"تم استرجاع الشاحنة المحذوفة بنجاح"]);
    }

    public function TruckTrashed(Request $request)
    {
        $TruckTrashed = Truck::onlyTrashed()->get();
        return response()->json($TruckTrashed, 200);
    }


}
