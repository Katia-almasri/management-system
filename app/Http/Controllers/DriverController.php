<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\DriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Traits\validationTrait;
use App\Models\Driver;
use Validator;
use Auth;

class DriverController extends Controller
{
    use validationTrait;

    //اضافة سائق
    public function AddDriver(DriverRequest $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:drivers,name',
            'address' => 'required',
            'mobile_number' => 'required|numeric',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $driver = new Driver();
        $driver->mashenism_coordinator_id = $request->user()->id;
        $driver->name = $request->name;
        $driver->address = $request->address;
        $driver->mobile_number = $request->mobile_number;
        $driver->state = 'متاح';
        $driver->save();
        return  response()->json(["status"=>true, "message"=>"تم اضافة سائق بنجاح"]);
    }

    //عرض السائقين المتوفرين
    public function displayDriver(Request $request){
        $displayDriver = Driver::get();
        return response()->json($displayDriver, 200);
    }

    public function displayAvaibaleDriver(Request $request){
        $displayDriver = Driver::where('state','متاح')->get();
        return response()->json($displayDriver, 200);
    }

    //حذف سائق
    public function SoftDeleteDriver(Request $request, $DriverId){
        Driver::find($DriverId)->delete();
       return  response()->json(["status"=>true, "message"=>"تم حذف سائق بنجاح"]);
   }

   //استرجاع سائق
   public function restoreDriver(Request $request, $DriverId)
   {
        Driver::onlyTrashed()->find($DriverId)->restore();
       return  response()->json(["status"=>true, "message"=>"تم استعادة سائق بنجاح"]);
   }
   //عرض السائقين المحذوفين
   public function DriverTrashed(Request $request)
   {
       $DriverTrashed = Driver::onlyTrashed()->get();
       return response()->json($DriverTrashed, 200);
   }

   //تعديل حالة سائق
   public function UpdateDriverState(UpdateDriverRequest $request,$DriverId){
        $validator = Validator::make($request->all(), [
            'state' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $DriverState = Driver::find($DriverId);
        $DriverState->state = $request->state;
        $DriverState->save();
        return  response()->json(["status"=>true, "message"=>"تم تحديث حالة السائق بنجاح"]);
}

}




