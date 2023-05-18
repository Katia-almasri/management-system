<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\Command;
use App\Models\CommandDetail;
use App\Models\DetonatorFrige1;
use App\Models\DetonatorFrige1Output;
use App\Models\DetonatorFrige2;
use App\Models\DetonatorFrige2Output;
use App\Models\DetonatorFrige3;
use App\Models\DetonatorFrige3Output;
use App\Models\Lake;
use App\Models\LakeDetail;
use App\Models\LakeOutput;
use App\Models\Store;
use App\Models\Warehouse;
use App\Models\ZeroFrige;
use App\Models\ZeroFrigeDetail;
use App\Models\ZeroFrigeOutput;
use App\systemServices\warehouseServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class WarehouseController extends Controller
{

    protected $warehouseService;

    public function __construct()
    {
        $this->warehouseService  = new warehouseServices();
    }

    //////////////////////// LAKES //////////////////////////////
    public function inputFromLakeToOutput(Request $request){

        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                    $result = $this->warehouseService->outputWeightFromLake($_detail, $request['outputChoice']);
                    if($result['status']==false)
                        throw new \ErrorException($result['message']);
                
            }
            DB::commit();
            return response()->json(["status" => true, "message" => "تمت عملية الإخراج بنجاح"]);
    }catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }

    }

    public function displayLakeContent(Request $request){
        $lakes = Lake::with('warehouse.outPut_Type_Production')
                    ->get();
        return response()->json($lakes);
    } 

    //////////////////// ZERO FRIGE //////////////////////
    public function displayZeroFrigeContent(Request $request){
        $zeroFriges = ZeroFrige::with('warehouse.outPut_Type_Production')
        ->get();
        return response()->json($zeroFriges);

    }

    public function inputFromZeroToOutput(Request $request){
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                    $result = $this->warehouseService->outputWeightFromZero($_detail, $request['outputChoice']);
                    if($result['status']==false)
                        throw new \ErrorException($result['message']);
                
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تمت عملية الإخراج بنجاح']);
    }catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }

    }

    //////////////////// DETONATOR 1 ////////////////////////

    public function  inputFromDet1ToOutput(Request $request){
            try {
                DB::beginTransaction();
                foreach ($request->details as $_detail) {
                        $result = $this->warehouseService->outputWeightFromDet1($_detail, $request['outputChoice']);
                        if($result['status']==false)
                            throw new \ErrorException($result['message']);
                }
                DB::commit();
                return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح']);
        }catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function displayDetonatorFrige1Content(){
        $detonatorFrige1 = DetonatorFrige1::with('warehouse.outPut_Type_Production')
        ->get();
        return response()->json($detonatorFrige1);

    }

    ///////////////////// DETONATOR 2 ////////////////////

    public function displayDetonatorFrige2Content(){
        $detonatorFrige2 = DetonatorFrige2::with('warehouse.outPut_Type_Production')
        ->get();
        return response()->json($detonatorFrige2);

    }

    public function  inputFromDet2ToOutput(Request $request){
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                    $result = $this->warehouseService->outputWeightFromDet2($_detail, $request['outputChoice']);
                   if($result['status']==false)
                        throw new \ErrorException($result['message']);
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح']);
    }catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }
}

////////////////////////// DETONATOR 3 ///////////////////////
    public function displayDetonatorFrige3Content(){
        $detonatorFrige3 = DetonatorFrige3::with('warehouse.outPut_Type_Production')
        ->get();
        return response()->json($detonatorFrige3);

    }

    public function  inputFromDet3ToOutput(Request $request){
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                    $result = $this->warehouseService->outputWeightFromDet3($_detail, $request['outputChoice']);
                   if($result['status']==false)
                        throw new \ErrorException($result['message']);
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح']);
    }catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }
}

    /////////////////// STORE CONTENT /////////////////////////
    public function displayStoreContent(){
        $store = Store::with('warehouse.outPut_Type_Production')
        ->get();
        return response()->json($store);

    }


    

    /////////////// WAREHOUSE FEATURES ///////////////

    public function displayWarehouseDetail(Request $request, $warehouseId){
        $warehouseDetail = Warehouse::where('id', $warehouseId)
                                    ->with(['zeroFrige', 'lake', 'detonatorFrige1', 'detonatorFrige2', 'detonatorFrige3', 'store'])->get();
        return response()->json($warehouseDetail);
    }
    public function editWarehouseRowInfo(Request $request, $warehouseId){

        $validator = Validator::make($request->all(),
        [
            "minimum"=>"numeric",
            "stockpile" => "numeric"      
        ]);
       if ($validator->fails()) {
           return response()->json(['status'=>false,
           'message'=>$validator->errors()->all()
       ]);
       }
        $warehouseRow = Warehouse::find($warehouseId);
        $warehouseRow->update(['minimum'=>$request['minimum'], 'stockpile'=>$request['stockpile']]);
        return response()->json(["status" => false, "message" =>'تم التعديل بنجاح']);
     
    }

    //////////////////// FILL COMMAND FROM PRODUCTION MAANAGER //////////////////
    public function fillCommandFromProductionManager(Request $request, $commandId){

        $from = $request['from'];
        try {
            DB::beginTransaction();
            foreach ($request['details'] as $_detail) {
                $result = $this->warehouseService->fillCommand($_detail, $from);
                if($result['status']!=true)
                throw new \ErrorException($result['message']);
            }
            DB::commit();
            $message = 'تمت العملية بنجاح';
            $doneCommand = $this->warehouseService->checkIsCommandDone($commandId);
            if($doneCommand['status']==true)
                $message= $message . ' و'.$doneCommand['message'];
            return response()->json(["status"=>true, "message"=>$message]);
        }catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
       
    }

    public function displayCommand(Request $request, $commandId){
        $command = Command::with(['commandDetails.warehouse.outPut_Type_Production'])->find($commandId);
        return response()->json($command);
    }

    public function displayWarehouseContentWithDetails(Request $request){
        $warehouse = Warehouse::with(['zeroFrige', 'lake', 'detonatorFrige1', 'detonatorFrige2', 'detonatorFrige3', 'store'])->get();
        return response()->json($warehouse);
    }
    /////////////////////// LAKE MOVEMENT (I/O) //////////////////
    //I
    public function displayLakeInputMov(Request $request){
        $lakeMovement = Lake::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'lakeDetails.inputable'])->get();
        return response()->json($lakeMovement);
    }
    //O    
    public function displayLakeOutMov(Request $request){
        $lakeMovement = LakeOutput::with(['lake.warehouse.outPut_Type_Production', 'outputable'])->get();
        return response()->json($lakeMovement);
    }

    /////////////////////// ZERO MOVEMENT (I/O) //////////////////
    public function displayZeroInputMov(Request $request){
        $zeroMovement = ZeroFrige::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'zeroFrigeDetails.inputable'])->get();
        return response()->json($zeroMovement);
    }

    public function displayZeroOutMov(Request $request){
        $lakeMovement = ZeroFrigeOutput::with(['zeroFrige.warehouse.outPut_Type_Production', 'outputable'])->get();
        return response()->json($lakeMovement);
    }
    
    /////////////////////// DET1 MOVEMENT (I/O) //////////////////
    public function displayDet1InputMov(Request $request){
        $det1Movement = DetonatorFrige1::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'detonatorFrige1Details.inputable'])->get();
        return response()->json($det1Movement);
    }

    public function displayDet1OutMov(Request $request){
        $det1Movement = DetonatorFrige1Output::with(['detonator1.warehouse.outPut_Type_Production', 'outputable'])->get();
        return response()->json($det1Movement);
    }

    /////////////////////// DET2 MOVEMENT (I/O) //////////////////
    public function displayDet2InputMov(Request $request){
        $det2Movement = DetonatorFrige2::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'detonatorFrige2Details.inputable'])->get();
        return response()->json($det2Movement);
    }

    public function displayDet2OutMov(Request $request){
        $det2Movement = DetonatorFrige2Output::with(['detonator2.warehouse.outPut_Type_Production', 'outputable'])->get();
        return response()->json($det2Movement);
    }

     /////////////////////// DET3 MOVEMENT (I/O) //////////////////
     public function displayDet3InputMov(Request $request){
        $det3Movement = DetonatorFrige3::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'detonatorFrige3Details.inputable'])->get();
        return response()->json($det3Movement);
    }

    public function displayDet3OutMov(Request $request){
        $det3Movement = DetonatorFrige3Output::with(['detonator3.warehouse.outPut_Type_Production', 'outputable'])->get();
        return response()->json($det3Movement);
    }

     /////////////////////// STORE MOVEMENT (I/O) //////////////////
     public function displayStoreInputMov(Request $request){
        $storeMovement = Store::where('weight', '!=', 0)->with(['warehouse.outPut_Type_Production', 'storeDetails.inputable'])->get();
        return response()->json($storeMovement);
    }
}   
