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
                $driver->state = 'ضمن العمل';
                $driver->save();
            return  response()->json(["status"=>true, "message"=>"driver created successfully"]);
    }

    public function displayDriver(Request $request){
        $displayDriver = Driver::get();
        return response()->json($displayDriver, 200);
    }


    public function SoftDeleteDriver(Request $request, $DriverId){
        Driver::find($DriverId)->delete();
       return  response()->json(["status"=>true, "message"=>"driver soft Deleted successfully"]);
   }

   public function restoreDriver(Request $request, $driverId)
   {
        Driver::withTrashed()->find($driverId)->restore();
       return  response()->json(["status"=>true, "message"=>"Restore Deleted successfully"]);
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
        return  response()->json(["status"=>true, "message"=>"state updated successfully"]);
}

}




