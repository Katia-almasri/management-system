<?php
namespace App\systemServices;

use App\Models\DetonatorFrige;
use App\Models\Lake;
use App\Models\LakeDetail;
use App\Models\LakeInputOutput;
use App\Models\LakeOutput;
use App\Models\outPut_SlaughterSupervisor_detail;
use App\Models\Warehouse;
use App\Models\ZeroFrige;
use App\Models\ZeroFrigeDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;

class warehouseServices{
    public function getDataFromOutputSlaughterId($slaughterId){
        $outPut_SlaughterSupervisor_detail = outPut_SlaughterSupervisor_detail::find($slaughterId);
        $isTypeExist = $this->checkIfTypeExist($outPut_SlaughterSupervisor_detail->type_id);
        if($isTypeExist == null)   
            return ([
            "status"=>false, 
            "message"=>"لا يوجد من هذه المادة في المخازن",
            "type_id"=>$outPut_SlaughterSupervisor_detail->type_id
        ]);

        return ([
            "status"=>true, 
            "message"=>"هذه المادة موجودة سابقاً في المخزن",
            "warehouse_id"=>$isTypeExist->id
        ]);

    }

    public function insertNewElementInLake($warehouseId){
        $lake = new Lake();
        $lake->warehouse_id = $warehouseId;
        $lake->amount = 0;
        $lake->weight = 0;
        $lake->save();
    }

    public function insertNewElementInDetonator($warehouseId){
        $detonator = new DetonatorFrige();
        $detonator->warehouse_id = $warehouseId;
        $detonator->amount = 0;
        $detonator->weight = 0;
        $detonator->save();
    }

    public function insertNewElementInZero($warehouseId){
        $zero = new ZeroFrige();
        $zero->warehouse_id = $warehouseId;
        $zero->amount = 0;
        $zero->weight = 0;
        $zero->save();
    }


    public function checkIfTypeExist($type_id){
        $typeId = Warehouse::where('type_id', $type_id)->get();
        if(!$typeId->isEmpty())
            return $typeId[0];
        return null;

    }

    public function storeNewInLake($warehouse_id, $outputSlaughterId){
        $lake = Lake::where('warehouse_id', $warehouse_id)->get();
        $outputSlaughterData = $this->getOutpuSlaughterDetailsData($outputSlaughterId);
        //INSERT INTO THE LAKE DETAIL TABLE
        $lakeDetails = new LakeDetail();
        $lakeDetails->lake_id = $lake[0]->id;
        $lakeDetails->weight = $outputSlaughterData->weight;
        $lakeDetails->cur_weight = $outputSlaughterData->weight;
        $lakeDetails->inputable_type = 'App\Models\outPut_SlaughterSupervisor_detail';
        $lakeDetails->inputable_id = $outputSlaughterId;
        $lakeDetails->save(); 

        $this->updateLakeWeightValue($lake[0]->id, $lake[0]->weight, $outputSlaughterData['weight']);
        $this->updateWarehouseValue($warehouse_id, $outputSlaughterData['weight']);
    }

    public function updateLakeWeightValue($lake_id, $curWeight, $newWeight){
        $lake = Lake::find($lake_id)->update(['weight'=>$curWeight + $newWeight]);
        return $curWeight + $newWeight;
    }

    public function updateWarehouseValue($warehouse_id, $newWeight){
        $warehouse = Warehouse::find($warehouse_id);
        $cureWeight = $warehouse->tot_weight;
        $warehouse->update(['tot_weight'=> $cureWeight + $newWeight]);
    }

    public function getOutpuSlaughterDetailsData($outputSlaughterId){
        $outputSlaughterData = outPut_SlaughterSupervisor_detail::find($outputSlaughterId);
        return $outputSlaughterData;
    }

    public function outputAmountFromLake($_detail){
        $lake_id = $_detail['lake_id'];

        $lake = Lake::find($lake_id);
        if($lake->amount!=null && $lake->amount < $_detail['amount'])
            return (["status"=>false, "message"=>"لا يوجد كمية كافية في المخزن"]);

        $flag = false;
        $elementsInLakeDetails = LakeDetail::where('lake_id', $lake_id)->orderBy('created_at', 'DESC')->get();
        $tot_amount = $_detail['amount'];
        foreach ($elementsInLakeDetails as $_lake_detail) {
            if($tot_amount != 0){
                if($_detail['amount'] >= $_lake_detail['cur_amount']){
                    $tot_amount -= $_lake_detail['cur_amount'];
                    $_lake_detail->update(['cur_amount'=>0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputLakeTable($_lake_detail['id'], null, $_lake_detail['amount']);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_lake_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                }
                else{
                    
                    $_lake_detail->update(['cur_amount'=>$_lake_detail['cur_amount'] - $tot_amount]);
                    $tot_amount = 0;
                    $flag = true;
                    break;
                }   
            }
            else{
                $flag = true;
                break;
            }            
        }
         
        if($tot_amount!=0){
            return (["status"=>false, "message"=>"هناك خطأ ما"]);  
             
        }
        else{
            //ENOUGH AMOUNT
            $lakeOutput = $this->insertNewRowInOutputLakeTable(null, $_detail['amount']);
            $this->setOutputIdToInputOutputLakeTable($lakeOutput->id);
            //UPDATE THE AMOUNT IN LAKE
            $lake->update(['amount'=> $lake['amount']- $_detail['amount']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($lake->warehouse_id);
            $warehouse->update(['tot_amount'=>$warehouse['tot_amount'] - $_detail['amount']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $this->inputFromLakeToZero($lakeOutput, $warehouse->id);
            return (["status"=>true, "message"=>"تم الإدخال بنجاح"]);
        }  
    }

    public function outputWeightFromLake($_detail){
        $lake_id = $_detail['lake_id'];

        $lake = Lake::find($lake_id);
        if($lake->weight!=null && $lake->weight < $_detail['weight'])
            return (["status"=>false, "message"=>"لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInLakeDetails = LakeDetail::where('lake_id', $lake_id)->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInLakeDetails as $_lake_detail) {
            if($tot_weight != 0){
                if($_detail['weight'] >= $_lake_detail['cur_weight']){
                    $tot_weight -= $_lake_detail['cur_weight'];
                    $_lake_detail->update(['cur_weight'=>0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputLakeTable($_lake_detail['id'], $_lake_detail['weight'], null);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_lake_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                }
                else{
                    
                    $_lake_detail->update(['cur_weight'=>$_lake_detail['cur_weight'] - $tot_weight]);
                    $tot_weight = 0;
                    $flag = true;
                    break;
                }   
            }
            else{
                $flag = true;
                break;
            }            
        }
         
        if($tot_weight!=0){
            return (["status"=>false, "message"=>"هناك خطأ ما"]);  
             
        }
        else{
            //ENOUGH AMOUNT
            $lakeOutput = $this->insertNewRowInOutputLakeTable($_detail['weight'], null);
            $this->setOutputIdToInputOutputLakeTable($lakeOutput->id);
            //UPDATE THE AMOUNT IN LAKE
            $lake->update(['weight'=> $lake['weight']- $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($lake->warehouse_id);
            $warehouse->update(['tot_weight'=>$warehouse['tot_weight'] - $_detail['weight']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $this->inputFromLakeToZero($lakeOutput, $warehouse->id);
            return (["status"=>true, "message"=>"تم الإدخال بنجاح"]);
        }  

    }

    public function inputFromLakeToZero($lakeOutput, $warehouseId){
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $zeroFrige = ZeroFrige::where('warehouse_id', $warehouseId)->get();
        $zeroFrigeDetail = new ZeroFrigeDetail();
        $zeroFrigeDetail->zero_frige_id = $zeroFrige[0]->id;
        $zeroFrigeDetail->weight= $lakeOutput->weight;
        $zeroFrigeDetail->amount= $lakeOutput->amount;
        $zeroFrigeDetail->cur_weight= $lakeOutput->weight;
        $zeroFrigeDetail->cur_amount= $lakeOutput->amount;
        $zeroFrigeDetail->inputable_type= 'App\Models\LakeOutput';
        $zeroFrigeDetail->inputable_id= $lakeOutput->id;
        $zeroFrigeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($zeroFrigeDetail);
        $lakeOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $zeroFrige[0]->update(['weight'=>$zeroFrige[0]->weight + $lakeOutput->weight, 
                            'amount'=>$zeroFrige[0]->amount + $lakeOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update(['tot_weight'=>$warehouse->tot_weight + $lakeOutput->weight, 
                            'tot_amount'=>$warehouse->tot_amount + $lakeOutput->amount
        ]);
        
    }

    public function insertNewRowInOutputLakeTable($weight, $amount){
        $lakeOutput = new LakeOutput();
        $lakeOutput->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $lakeOutput->outputable_type = 'App\Models\ZeroFrigeDetail';
        $lakeOutput->outputable_id = 0;
        $lakeOutput->weight = $weight;
        $lakeOutput->amount = $amount;
        $lakeOutput->save();
        return $lakeOutput;
    }
    
    public function insertNewRowInInputOutputLakeTable($input_id, $weight, $amount){
        $inputOutputLakeElement = new LakeInputOutput();
        $inputOutputLakeElement->input_id = $input_id;
        $inputOutputLakeElement->weight = $weight;
        $inputOutputLakeElement->amount = $amount;
        $inputOutputLakeElement->save();
        return $inputOutputLakeElement->id;
    }

    public function setOutputIdToInputOutputLakeTable($lakeOutputId){
        $inputOutputLakeElements = LakeInputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputLakeElements as $_inputOutput) {
            $_inputOutput->update(['output_id'=>$lakeOutputId]);
        }
        return true;
    }

    public function addNewTypeInWarehouse($type_id){
        $warehouse = new Warehouse();
        $warehouse->type_id = $type_id;
        $warehouse->save();

        $this->insertNewElementInDetonator($warehouse->id);
        $this->insertNewElementInLake($warehouse->id);
        $this->insertNewElementInZero($warehouse->id);
        return true;
    }

}