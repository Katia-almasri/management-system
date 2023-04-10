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

    public function AddDriver(DriverRequest $request){

                $driver = new Driver();
                $driver->mashenism_coordinator_id = $request->user()->id;
                $driver->name = $request->name;
                $driver->state = 'متاح';
                $driver->save();
            return  response()->json(["status"=>true, "message"=>"تم اضافة سائق بنجاح"]);
    }

    public function displayDriver(Request $request){
        $displayDriver = Driver::get();
        return response()->json($displayDriver, 200);
    }


    public function SoftDeleteDriver(Request $request, $DriverId){
        Driver::find($DriverId)->delete();
       return  response()->json(["status"=>true, "message"=>"تم حذف سائق بنجاح"]);
   }

   public function restoreDriver(Request $request, $DriverId)
   {
        Driver::withTrashed()->find($DriverId)->restore();
       return  response()->json(["status"=>true, "message"=>"تم استعادة سائق بنجاح"]);
   }

   public function DriverTrashed(Request $request)
   {
       $DriverTrashed = Driver::onlyTrashed()->get();
       return response()->json($DriverTrashed, 200);
   }

   public function UpdateDriverState(UpdateDriverRequest $request,$DriverId){

        $DriverState = Driver::find($DriverId);
        $DriverState->state = $request->state;
        $DriverState->save();
        return  response()->json(["status"=>true, "message"=>"تم تحديث حالة السائق بنجاح"]);
}

}




