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
    
            $truck = new Truck();
            $truck->mashenism_coordinator_id = $request->user()->id;
            $truck->name = $request->name;
            $truck->model = $request->model;
            $truck->storage_capacity = $request->storage_capacity;
            $truck->state = 'متاحة';
            $truck->save();
        return  response()->json(["status"=>true, "message"=>"truck created successfully"]);
    }

    public function displayTruck(Request $request){
        $displayTrucks = Truck::get();
        return response()->json($displayTrucks, 200);
    }

    public function UpdateTruckState(UpdateTruckRequest $request,$TruckId){

        $TrunkState = Truck::find($TruckId);
        $TrunkState->state = $request->state;
        $TrunkState->save();
        return  response()->json(["status"=>true, "message"=>"state updated successfully"]);
    }

    public function SoftDeleteTruck(Request $request, $TruckId){
         Truck::find($TruckId)->delete();
        return  response()->json(["status"=>true, "message"=>"truck soft Deleted successfully"]);
    }

    public function restoreTruck(Request $request, $TruckId)
    {
        Truck::withTrashed()->find($TruckId)->restore();

        return  response()->json(["status"=>true, "message"=>"Restore Deleted successfully"]);
    }

    public function TruckTrashed(Request $request)
    {
        $TruckTrashed = Truck::onlyTrashed()->get();
        return response()->json($TruckTrashed, 200);
    }


}
