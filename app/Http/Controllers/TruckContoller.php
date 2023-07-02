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

    //اضافة شاحنة
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
    //عرض الشاحنات
    public function displayTruck(Request $request){
        $displayTrucks = Truck::get();
        return response()->json($displayTrucks, 200);
    }

    /////عرض الشاحنات المتاحة
    public function displayAvaibaleTruck(Request $request){
        $displayTrucks = Truck::where('state','متاحة')->get();
        return response()->json($displayTrucks, 200);
    }
    //تغيير حالة شاحنة
    public function UpdateTruckState(UpdateTruckRequest $request,$TruckId){
        $validator = Validator::make($request->all(), [
            'state' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $TrunkState = Truck::find($TruckId);
        $TrunkState->state = $request->state;
        $TrunkState->save();
        return  response()->json(["status"=>true, "message"=>"تم تعديل حالة الشاحنة بنجاح"]);
    }
    // حذف شاحنة
    public function SoftDeleteTruck(Request $request, $TruckId){
         Truck::find($TruckId)->delete();
        return  response()->json(["status"=>true, "message"=>"تم حذف الشاحنة بنجاح"]);
    }
    // استرجاع شاحنة محذوفة
    public function restoreTruck(Request $request, $TruckId)
    {
        Truck::onlyTrashed()->find($TruckId)->restore();

        return  response()->json(["status"=>true, "message"=>"تم استرجاع الشاحنة المحذوفة بنجاح"]);
    }
    // عرض الشاحنات المحذوفة
    public function TruckTrashed(Request $request)
    {
        $TruckTrashed = Truck::onlyTrashed()->get();
        return response()->json($TruckTrashed, 200);
    }

    public function getTruckStates(Request $request){
        return response()->json(["متاحة", "في الرحلة", "في الصيانة"]);
    }

    public function getDriverStates(Request $request){
        return response()->json(["متاح", "في الرحلة", "إجازة", "دوام"]);
    }


}
