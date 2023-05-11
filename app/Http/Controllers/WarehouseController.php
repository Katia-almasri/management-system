<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\LakeDetail;
use App\Models\Warehouse;
use App\Models\ZeroFrigeDetail;
use App\systemServices\warehouseServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WarehouseController extends Controller
{

    protected $warehouseService;

    public function __construct()
    {
        $this->warehouseService  = new warehouseServices();
    }

    ////////////////////////INPUT  LAKES //////////////////////////////

    public function setNewFromSlaughterToLakes(Request $request){
        
        //1. foreach detail:
            /*[
                fetch the weight and type from id
                if the type is new: then insert into warehouse, lakes lake_details
                then:{
                    (inputable, request->id, type = App\Models\outputslaughter)
                    check if this
                    update values

                }
            ]
            */
            foreach($request->details as $_detail){
                $isTypeExist = $this->warehouseService->getDataFromOutputSlaughterId($_detail);
                if($isTypeExist['status']==false){
                    response()->json(["status" => false, "message" => $isTypeExist['type_id']]);
                }
                else{
                    //THIS TYPE IS IN WAREHOUSE: THEN CONTINE
                    $warehouse_id = $isTypeExist['warehouse_id'];
                    $this->warehouseService->storeNewInLake($warehouse_id, $_detail);
                }
            }
    }

    public function insertNewElementInWarehouse(WarehouseRequest $request){

        try { 
            $typeExist = $this->warehouseService->checkIfTypeExist($request['type_id']);
            if($typeExist != null)
                throw new \ErrorException("هذه المادة موجودة سابقاً في المخزن");
            else{
                //CHECK IF THIS TYPE ALREADY IN WAREHOUSE
                DB::beginTransaction();     
                $warehouseElement = new Warehouse();
                $warehouseElement->type_id = $request['type_id'];
                if($request['stockpile']!=null)
                    $warehouseElement->stockpile = $request['stockpile'];
                if($request['minimum']!=null)
                    $warehouseElement->minimum = $request['minimum'];

                $warehouseElement->tot_amount = 0;
                $warehouseElement->tot_weight = 0;
                $warehouseElement->save();
                
                //CREATE NEW ELEMENT IN ALL WAREHOUSES
                //1. lake
                //2. zero
                //3. ...

                $lake = $this->warehouseService->insertNewElementInLake($warehouseElement->id);
                $detonatorFrige = $this->warehouseService->insertNewElementInDetonator($warehouseElement->id);
                $zeroFrige = $this->warehouseService->insertNewElementInZero($warehouseElement->id);
                DB::commit();
                return ["status" => true, "message" => "تم إدخال مادة جديدة إلى المحزن بنجاح"];

            }
    } catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }
        
        
    }

    public function setNewFromCuttingToLakes(){

    }

    public function setNewFromMAnufacturingToLakes(){

    }

    //INPUT FROM WAREHOUSE SECTIONS 
            //1. FROM LAKE TO ZERO (tommowrow)
    public function  inputFromLakeToZeroFrige(Request $request){
        //1. OUTPUT FROM LAKE
        //2. UPDATE VALUES
        /*
        foreach details as detail{
            if(weight = 0) // take the amount{
                lake_detail = detail[lake_detail_id]
                insert new row in output_lake_table
                 * output_lake_table_id = id
                through all elements in lake_Detail table: {
                    1 sum to reach the amount
                    2 sub from this row
                    3 if lake detail id amount is zero: then {
                        11. insert into input_output_lakes with output_lake_id = *
                        22. update the output date in lake_detail_id
                    }

                }
                when reach then:{
                    update into output lake where id = * 
                    update the sum
                    update input output lake where sign_out date != null and output_lake = *
                }
            }
            else if (amount = 0) //take the weight:
                THE SAME
        }
        */

        foreach ($request->details as $_detail) {
            if($_detail['weight'] == 0){
                $result = $this->warehouseService->outpuFromLake($_detail);
                return response()->json($result['message']);
                //process the amount
            }
        }
        
       
        //INPUT TO ZERO
        //UPDATE VALUES
    }
    ////////////////////INPUT DETONATOR ////////////////////////
    //INPUT FROM WAREHOUSE SECTIONS
    //////////////////INPUT ZERO //////////////////////////////
    //INPUT FROM WAREHOUSE SECTIONS


    //DISPLAY LAKE CONTENT (tommowrow)
    public function displayLakeDetailsMovement(Request $request, $lakeId){
        $lakeDetails = LakeDetail::with('inputable')
                        ->where('lake_id', $lakeId)->get();
        return response()->json($lakeDetails);
    }
    //DISPLAY DETONATOR CONTENT 
    //DISPLAY ZERO CONTENT (tommowrow)
    public function displayZeroDetailsMovement(Request $request, $zeroId){
        $zeroDetails = ZeroFrigeDetail::with('inputable')
                        ->where('zero_frige_id', $zeroId)->get();
        return response()->json($zeroDetails);
    }
    
    //DISPLAY WAREHOUSE (ALL)

    //////////////// OUTPUT //////////////////(later)
    /////////////// warehouse movement //////(later)

    
}   
