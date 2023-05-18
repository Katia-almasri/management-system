<?php
namespace App\systemServices;

use App\Models\Command;
use App\Models\CommandDetail;
use App\Models\DetonatorFrige;
use App\Models\DetonatorFrige1;
use App\Models\DetonatorFrige1Detail;
use App\Models\DetonatorFrige1InputOutput;
use App\Models\DetonatorFrige1Output;
use App\Models\DetonatorFrige2;
use App\Models\DetonatorFrige2Detail;
use App\Models\DetonatorFrige2InputOutput;
use App\Models\DetonatorFrige2Output;
use App\Models\DetonatorFrige3;
use App\Models\DetonatorFrige3Detail;
use App\Models\DetonatorFrige3InputOutput;
use App\Models\DetonatorFrige3Output;
use App\Models\FillCommand;
use App\Models\InputCutting;
use App\Models\InputManufacturing;
use App\Models\Lake;
use App\Models\LakeDetail;
use App\Models\LakeInputOutput;
use App\Models\LakeOutput;
use App\Models\outPut_SlaughterSupervisor_detail;
use App\Models\Store;
use App\Models\StoreDetail;
use App\Models\Warehouse;
use App\Models\ZeroFrige;
use App\Models\ZeroFrigeDetail;
use App\Models\ZeroFrigeInputOutput;
use App\Models\ZeroFrigeOutput;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Auth;
use Illuminate\Http\Request;

class warehouseServices
{

    ///////////////////////// GENERAL ///////////////////////////
    public function insertNewElementInLake($warehouseId)
    {
        $lake = new Lake();
        $lake->warehouse_id = $warehouseId;
        $lake->amount = 0;
        $lake->weight = 0;
        $lake->save();
    }

    public function insertNewElementInDetonator1($warehouseId)
    {
        $detonator = new DetonatorFrige1();
        $detonator->warehouse_id = $warehouseId;
        $detonator->amount = 0;
        $detonator->weight = 0;
        $detonator->save();
    }

    public function insertNewElementInDetonator2($warehouseId)
    {
        $detonator = new DetonatorFrige2();
        $detonator->warehouse_id = $warehouseId;
        $detonator->amount = 0;
        $detonator->weight = 0;
        $detonator->save();
    }

    public function insertNewElementInDetonator3($warehouseId)
    {
        $detonator = new DetonatorFrige3();
        $detonator->warehouse_id = $warehouseId;
        $detonator->amount = 0;
        $detonator->weight = 0;
        $detonator->save();
    }

    public function insertNewElementInZero($warehouseId)
    {
        $zero = new ZeroFrige();
        $zero->warehouse_id = $warehouseId;
        $zero->amount = 0;
        $zero->weight = 0;
        $zero->save();
    }


    public function checkIfTypeExist($type_id)
    {
        $typeId = Warehouse::where('type_id', $type_id)->get();
        if (!$typeId->isEmpty())
            return $typeId[0];
        return null;

    }

    public function storeNewInLake($warehouse_id, $outputSlaughterId)
    {
        $lake = Lake::where('warehouse_id', $warehouse_id)->get();
        $outputSlaughterData = $this->getOutpuSlaughterDetailsData($outputSlaughterId);
        //INSERT INTO THE LAKE DETAIL TABLE
        $lakeDetails = new LakeDetail();
        $lakeDetails->lake_id = $lake[0]->id;
        $lakeDetails->weight = $outputSlaughterData->weight;
        $lakeDetails->cur_weight = $outputSlaughterData->weight;
        $lakeDetails->inputable_type = 'App\Models\outPut_SlaughterSupervisor_detail';
        $lakeDetails->inputable_id = $outputSlaughterId;
        $lakeDetails->input_from = 'قسم الذبح';
        $lakeDetails->save();

        $this->updateLakeWeightValue($lake[0]->id, $lake[0]->weight, $outputSlaughterData['weight']);
        $this->updateWarehouseValue($warehouse_id, $outputSlaughterData['weight']);
    }

    public function updateLakeWeightValue($lake_id, $curWeight, $newWeight)
    {
        $lake = Lake::find($lake_id)->update(['weight' => $curWeight + $newWeight]);
        return $curWeight + $newWeight;
    }

    public function updateWarehouseValue($warehouse_id, $newWeight)
    {
        $warehouse = Warehouse::find($warehouse_id);
        $cureWeight = $warehouse->tot_weight;
        $warehouse->update(['tot_weight' => $cureWeight + $newWeight]);
    }

    public function getOutpuSlaughterDetailsData($outputSlaughterId)
    {
        $outputSlaughterData = outPut_SlaughterSupervisor_detail::find($outputSlaughterId);
        return $outputSlaughterData;
    }

    public function getModel($outputChoice)
    {
        if ($outputChoice == 'صاعقة 1') {
            return 'App\Models\DetonatorFrige1Detail';
        }
         else if ($outputChoice == 'صاعقة 2') {
            return 'App\Models\DetonatorFrige2Detail';
        }
         else if ($outputChoice == 'صاعقة 3') {
            return 'App\Models\DetonatorFrige3Detail';
        }
         else if ($outputChoice == 'براد صفري') {
            return 'App\Models\ZeroFrigeDetail';
        }
        else if ($outputChoice == 'بحرات') {
            return 'App\Models\LakeDetail';
        }
        else if ($outputChoice == 'تخزين') {
            return 'App\Models\StoreDetail';
        }

        else if ($outputChoice == 'التقطيع') {
            return 'App\Models\InputCutting';
        }

        else if ($outputChoice == 'تصنيع') {
            return 'App\Models\InputManufacturing';
        }

    }
    ///////////////////// LAKE //////////////////////////////
    public function outputWeightFromLake($_detail, $outputChoice)
    {
        $lake_id = $_detail['lake_id'];
        $lake = Lake::find($lake_id);
        if (is_null($lake->weight) ||  $lake->weight < $_detail['weight'])
            return (["status" => false, "message" => "لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInLakeDetails = LakeDetail::where([['lake_id', $lake_id], ['cur_weight', '!=', 0]])->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInLakeDetails as $_lake_detail) {
            if ($tot_weight != 0) {
                if ($tot_weight >= $_lake_detail['cur_weight']) {
                    $tot_weight -= $_lake_detail['cur_weight'];
                    $_lake_detail->update(['cur_weight' => 0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputLakeTable($_lake_detail['id'], $_lake_detail['weight'], null);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_lake_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                } else {

                    $_lake_detail->update(['cur_weight' => $_lake_detail['cur_weight'] - $tot_weight]);
                    $tot_weight = 0;
                    $flag = true;
                    break;
                }
            } else {
                $flag = true;
                break;
            }
        }

        if ($tot_weight != 0) {
            return (["status" => false, "message" => "الوزن المدخل أكبر من الموجود في المخازن"]);

        } else {
            $model = $this->getModel($outputChoice);

            //ENOUGH AMOUNT
            $lakeOutput = $this->insertNewRowInOutputLakeTable($_detail['weight'], null, $model, $lake_id);
            $this->setOutputIdToInputOutputLakeTable($lakeOutput->id);
            //UPDATE THE AMOUNT IN LAKE
            $lake->update(['weight' => $lake['weight'] - $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($lake->warehouse_id);
            $type_id = $warehouse->type_id;
            $warehouse->update(['tot_weight' => $warehouse['tot_weight'] - $_detail['weight']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $result = [];

            if ($outputChoice == 'صاعقة 1') {
               $result =  $this->inputFromLakeToDetonator1($lakeOutput, $warehouse->id);
            } else if ($outputChoice == 'صاعقة 2') {
                $result = $this->inputFromLakeToDetonator2($lakeOutput, $warehouse->id);
            } else if ($outputChoice == 'صاعقة 3') {
                $result = $this->inputFromLakeToDetonator3($lakeOutput, $warehouse->id);
            } else if ($outputChoice == 'براد صفري') {
                $result = $this->inputFromLakeToZero($lakeOutput, $warehouse->id);
            }
            else if ($outputChoice == 'تصنيع') {
                $result = $this->inputFromLakeToManufactoring($lakeOutput, $warehouse->id, $type_id);
            }
            else if ($outputChoice == 'التقطيع') {
                $result = $this->inputFromLakeToCutting($lakeOutput, $warehouse->id, $type_id);
            }
            return (["status" => true, "message" =>$result]);
        }

    }

    public function inputFromLakeToDetonator1($lakeOutput, $warehouseId)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $DetonatorFrige1 = DetonatorFrige1::where('warehouse_id', $warehouseId)->get();
        $DetonatorFrige1Detail = new DetonatorFrige1Detail();
        $DetonatorFrige1Detail->detonator_frige_1_id = $DetonatorFrige1[0]->id;
        $DetonatorFrige1Detail->weight = $lakeOutput->weight;
        $DetonatorFrige1Detail->amount = $lakeOutput->amount;
        $DetonatorFrige1Detail->cur_weight = $lakeOutput->weight;
        $DetonatorFrige1Detail->cur_amount = $lakeOutput->amount;
        $DetonatorFrige1Detail->inputable_type = 'App\Models\LakeOutput';
        $DetonatorFrige1Detail->inputable_id = $lakeOutput->id;
        $DetonatorFrige1Detail->input_from = 'مستودع البحرات';
        $DetonatorFrige1Detail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($DetonatorFrige1Detail);
        $lakeOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $DetonatorFrige1[0]->update([
            'weight' => $DetonatorFrige1[0]->weight + $lakeOutput->weight,
            'amount' => $DetonatorFrige1[0]->amount + $lakeOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $lakeOutput->weight,
            'tot_amount' => $warehouse->tot_amount + $lakeOutput->amount
        ]);

        return (["status" => true, "output" => $lakeOutput]);

    }

    public function inputFromLakeToDetonator2($lakeOutput, $warehouseId)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $DetonatorFrige2 = DetonatorFrige2::where('warehouse_id', $warehouseId)->get();
        $DetonatorFrige2Detail = new DetonatorFrige2Detail();
        $DetonatorFrige2Detail->detonator_frige_2_id = $DetonatorFrige2[0]->id;
        $DetonatorFrige2Detail->weight = $lakeOutput->weight;
        $DetonatorFrige2Detail->amount = $lakeOutput->amount;
        $DetonatorFrige2Detail->cur_weight = $lakeOutput->weight;
        $DetonatorFrige2Detail->cur_amount = $lakeOutput->amount;
        $DetonatorFrige2Detail->inputable_type = 'App\Models\LakeOutput';
        $DetonatorFrige2Detail->inputable_id = $lakeOutput->id;
        $DetonatorFrige2Detail->input_from = 'مستودع البحرات';
        $DetonatorFrige2Detail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($DetonatorFrige2Detail);
        $lakeOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $DetonatorFrige2[0]->update([
            'weight' => $DetonatorFrige2[0]->weight + $lakeOutput->weight,
            'amount' => $DetonatorFrige2[0]->amount + $lakeOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $lakeOutput->weight,
            'tot_amount' => $warehouse->tot_amount + $lakeOutput->amount
        ]);
        return (["status" => true, "output" => $lakeOutput]);
    }

    public function inputFromLakeToDetonator3($lakeOutput, $warehouseId)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $DetonatorFrige3 = DetonatorFrige3::where('warehouse_id', $warehouseId)->get();
        $DetonatorFrige3Detail = new DetonatorFrige3Detail();
        $DetonatorFrige3Detail->detonator_frige_3_id = $DetonatorFrige3[0]->id;
        $DetonatorFrige3Detail->weight = $lakeOutput->weight;
        $DetonatorFrige3Detail->amount = $lakeOutput->amount;
        $DetonatorFrige3Detail->cur_weight = $lakeOutput->weight;
        $DetonatorFrige3Detail->cur_amount = $lakeOutput->amount;
        $DetonatorFrige3Detail->inputable_type = 'App\Models\LakeOutput';
        $DetonatorFrige3Detail->inputable_id = $lakeOutput->id;
        $DetonatorFrige3Detail->input_from = 'مستودع البحرات';
        $DetonatorFrige3Detail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($DetonatorFrige3Detail);
        $lakeOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $DetonatorFrige3[0]->update([
            'weight' => $DetonatorFrige3[0]->weight + $lakeOutput->weight,
            'amount' => $DetonatorFrige3[0]->amount + $lakeOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $lakeOutput->weight,
            'tot_amount' => $warehouse->tot_amount + $lakeOutput->amount
        ]);

        return (["status" => true, "output" => $lakeOutput]);

    }

    public function insertNewRowInInputOutputLakeTable($input_id, $weight, $amount)
    {
        $inputOutputLakeElement = new LakeInputOutput();
        $inputOutputLakeElement->input_id = $input_id;
        $inputOutputLakeElement->weight = $weight;
        $inputOutputLakeElement->amount = $amount;
        $inputOutputLakeElement->save();
        return $inputOutputLakeElement->id;
    }

    public function insertNewRowInOutputLakeTable($weight, $amount, $model, $lake_id)
    {
        $lakeOutput = new LakeOutput();
        $lakeOutput->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $lakeOutput->outputable_type = $model;
        $lakeOutput->outputable_id = 0;
        $lakeOutput->weight = $weight;
        $lakeOutput->amount = $amount;
        $lakeOutput->lake_id = $lake_id;
        $lakeOutput->save();
        return $lakeOutput;
    }

    public function inputFromLakeToZero($lakeOutput, $warehouseId)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $zeroFrige = ZeroFrige::where('warehouse_id', $warehouseId)->get();
        $zeroFrigeDetail = new ZeroFrigeDetail();
        $zeroFrigeDetail->zero_frige_id = $zeroFrige[0]->id;
        $zeroFrigeDetail->weight = $lakeOutput->weight;
        $zeroFrigeDetail->amount = $lakeOutput->amount;
        $zeroFrigeDetail->cur_weight = $lakeOutput->weight;
        $zeroFrigeDetail->cur_amount = $lakeOutput->amount;
        $zeroFrigeDetail->inputable_type = 'App\Models\LakeOutput';
        $zeroFrigeDetail->inputable_id = $lakeOutput->id;
        $zeroFrigeDetail->input_from = 'مستودع البحرات';
        $zeroFrigeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($zeroFrigeDetail);
        $lakeOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $zeroFrige[0]->update([
            'weight' => $zeroFrige[0]->weight + $lakeOutput->weight,
            'amount' => $zeroFrige[0]->amount + $lakeOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $lakeOutput->weight,
            'tot_amount' => $warehouse->tot_amount + $lakeOutput->amount
        ]);

        return (["status" => true, "output" => $lakeOutput]);

    }

    public function inputFromLakeToManufactoring($lakeOutput, $warehouseId, $type_id)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $inputManufactoring = new InputManufacturing();
        $inputManufactoring->weight = $lakeOutput->weight;
        $inputManufactoring->type_id = $type_id;
        $inputManufactoring->inputable_type = 'App\Models\LakeDetail';
        $inputManufactoring->inputable_id = $lakeOutput->id;
        $inputManufactoring->input_from = 'مستودع البحرات';
        $inputManufactoring->save();
        

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($inputManufactoring);
        $lakeOutput->save();
        return (["status" => true, "output" => $lakeOutput]);
    }

    public function inputFromLakeToCutting($lakeOutput, $warehouseId, $type_id)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $inputCutting = new InputCutting();
        $inputCutting->weight = $lakeOutput->weight;
        $inputCutting->type_id = $type_id;
        $inputCutting->inputable_type = 'App\Models\LakeDetail';
        $inputCutting->inputable_id = $lakeOutput->id;
        $inputCutting->input_from = 'مستودع البحرات';
        $inputCutting->save();
        

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $lakeOutput->outputable()->associate($inputCutting);
        $lakeOutput->save();
        return (["status" => true, "output" => $lakeOutput]);
    }

    ///////////////////////////////////////////////////////////////////////////////////
    
    public function setOutputIdToInputOutputLakeTable($lakeOutputId)
    {
        $inputOutputLakeElements = LakeInputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputLakeElements as $_inputOutput) {
            $_inputOutput->update(['output_id' => $lakeOutputId]);
        }
        return true;
    }

    public function addNewTypeInWarehouse($type_id)
    {
        $warehouse = new Warehouse();
        $warehouse->type_id = $type_id;
        $warehouse->save();

        $this->insertNewElementInDetonator1($warehouse->id);
        $this->insertNewElementInDetonator2($warehouse->id);
        $this->insertNewElementInDetonator3($warehouse->id);
        $this->insertNewElementInLake($warehouse->id);
        $this->insertNewElementInZero($warehouse->id);
        return true;
    }
    /////////////////////////////// ZERO ////////////////////////////////

    public function outputWeightFromZero($_detail, $outputChoice)
    {
        $zero_id = $_detail['zero_id'];
        
        $zero = ZeroFrige::find($zero_id);
        if (is_null($zero->weight) ||  $zero->weight < $_detail['weight'])
            return (["status" => false, "message" => "لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInZeroDetails = ZeroFrigeDetail::where([['zero_frige_id', $zero_id], ['cur_weight', '!=', 0]])->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInZeroDetails as $_zero_detail) {
            if ($tot_weight != 0) {
                if ($tot_weight >= $_zero_detail['cur_weight']) {
                    $tot_weight -= $_zero_detail['cur_weight'];
                    $_zero_detail->update(['cur_weight' => 0]);
                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_zero TABLE
                    $this->insertNewRowInInputOutputZeroTable($_zero_detail['id'], $_zero_detail['weight'], null);
                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_zero_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                } else{
                    
                        $_zero_detail->update(['cur_weight' => $_zero_detail['cur_weight'] - $tot_weight]);
                        $tot_weight = 0;
                        $flag = true;
                        break;
                    
                } 

            } else {
                $flag = true;
                break;
            }
        }

        if ($tot_weight != 0) {
            return (["status" => false, "message" => 'الوزن المدخل أكبر من الموجود في المخازن']);

        } else {
            $model = $this->getModel($outputChoice);
            //ENOUGH WEIGHT
            $zeroOutput = $this->insertNewRowInOutputZeroTable($_detail['weight'], null, $model, $zero_id);
            $this->setOutputIdToInputOutputZeroTable($zeroOutput->id);
            //UPDATE THE AMOUNT IN ZERO
            $zero->update(['weight' => $zero['weight'] - $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($zero->warehouse_id);
            $type_id = $warehouse->type_id;
            $warehouse->update(['tot_weight' => $warehouse['tot_weight'] - $_detail['weight']]);
            //USE $zeroOutput WHEN INSERT INPUT IN DETONATOR1 FRIGE:
            /**
             * now the input from lake to zero
            */
            $result = [];
            if ($outputChoice == 'بحرات') {
                //edit return type to this method
                $result = $this->inputFromZeroToLake($zeroOutput, $warehouse->id);
            }
            else if($outputChoice=='التقطيع'){
                $result = $this->inputFromZeroToCutting($zeroOutput, $warehouse->id, $type_id);
            }

            else if($outputChoice=='تصنيع'){
                $result = $this->inputFromZeroToManufactoring($zeroOutput, $warehouse->id, $type_id);
            }

            return (["status" => true, "message" => $result]);

        }
    }

    public function inputFromZeroToLake($zeroOutput, $warehouseId)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $lake = Lake::where('warehouse_id', $warehouseId)->get();
        $LakeDetail = new LakeDetail();
        $LakeDetail->lake_id = $lake[0]->id;
        $LakeDetail->weight = $zeroOutput->weight;
        $LakeDetail->amount = $zeroOutput->amount;
        $LakeDetail->cur_weight = $zeroOutput->weight;
        $LakeDetail->cur_amount = $zeroOutput->amount;
        $LakeDetail->inputable_type = 'App\Models\ZeroFrigeOutput';
        $LakeDetail->inputable_id = $zeroOutput->id;
        $LakeDetail->input_from = 'البراد الصفري';
        $LakeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $zeroOutput->outputable()->associate($LakeDetail);
        $zeroOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $lake[0]->update([
            'weight' => $lake[0]->weight + $zeroOutput->weight,
            'amount' => $lake[0]->amount + $zeroOutput->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $zeroOutput->weight,
            'tot_amount' => $warehouse->tot_amount + $zeroOutput->amount
        ]);
        return (["status" => true, "output" => $zeroOutput]);
    }

    public function inputFromZeroToCutting($zeroOutput, $warehouseId, $type_id)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $inputCutting = new InputCutting();
        $inputCutting->weight = $zeroOutput->weight;
        $inputCutting->cutting_done = 0;
        $inputCutting->type_id = $type_id;
        $inputCutting->inputable_type = 'App\Models\ZeroFrigeOutput';
        $inputCutting->inputable_id = $zeroOutput->id;
        $inputCutting->input_from = 'البراد الصفري';
       
        $inputCutting->save();
        

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $zeroOutput->outputable()->associate($inputCutting);
        $zeroOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        return (["status" => true, "output" => $zeroOutput]);
    }

    public function inputFromZeroToManufactoring($zeroOutput, $warehouseId, $type_id)
    {
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $inputManufactoring = new InputManufacturing();
        $inputManufactoring->weight = $zeroOutput->weight;
        $inputManufactoring->type_id = $type_id;
        $inputManufactoring->inputable_type = 'App\Models\ZeroFrigeOutput';
        $inputManufactoring->inputable_id = $zeroOutput->id;
        $inputManufactoring->input_from = 'البراد الصفري';
        $inputManufactoring->save();
        

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $zeroOutput->outputable()->associate($inputManufactoring);
        $zeroOutput->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        return (["status" => true, "output" => $zeroOutput]);
    }


 
    public function insertNewRowInInputOutputZeroTable($input_id, $weight, $amount)
    {
        $inputOutputZeroElement = new ZeroFrigeInputOutput();
        $inputOutputZeroElement->input_id = $input_id;
        $inputOutputZeroElement->weight = $weight;
        $inputOutputZeroElement->amount = $amount;
        $inputOutputZeroElement->save();
        return $inputOutputZeroElement->id;

    }

    public function insertNewRowInOutputZeroTable($weight, $amount, $model, $zero_id)
    {
        $zeroOutput = new ZeroFrigeOutput();
        $zeroOutput->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $zeroOutput->outputable_type = $model;
        $zeroOutput->outputable_id = 0;
        $zeroOutput->weight = $weight;
        $zeroOutput->amount = $amount;
        $zeroOutput->zero_id = $zero_id;
        $zeroOutput->save();
        return $zeroOutput;

    }

    public function setOutputIdToInputOutputZeroTable($zeroOutputId)
    {
        $inputOutputZeroElements = ZeroFrigeInputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputZeroElements as $_inputOutput) {
            $_inputOutput->update(['output_id' => $zeroOutputId]);
        }
        return true;
    }

    ////////////////////////////// DET 1 ////////////////////////////

    public function outputWeightFromDet1($_detail, $outputChoice){
        $det1_id = $_detail['det_id'];
        $det1 = DetonatorFrige1::find($det1_id);
        if (is_null($det1->weight) ||  $det1->weight < $_detail['weight'])
            return (["status" => false, "message" => "لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInDetonatorDetails = DetonatorFrige1Detail::where([['detonator_frige_1_id', $det1_id], ['cur_weight', '!=', 0]])->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInDetonatorDetails as $_det1_detail) {
            if ($tot_weight != 0) {
                if ($tot_weight >= $_det1_detail['cur_weight']) {
                    $tot_weight -= $_det1_detail['cur_weight'];
                    $_det1_detail->update(['cur_weight' => 0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputDet1Table($_det1_detail['id'], $_det1_detail['weight'], null);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_det1_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                } else {

                    $_det1_detail->update(['cur_weight' => $_det1_detail['cur_weight'] - $tot_weight]);
                    $tot_weight = 0;
                    $flag = true;
                    break;
                }
            } else {
                $flag = true;
                break;
            }
        }

        if ($tot_weight != 0) {
            return (["status" => false, "message" => $tot_weight]);

        } else {
            $model = $this->getModel($outputChoice);

            //ENOUGH AMOUNT
            $det1Output = $this->insertNewRowInOutputDet1Table($_detail['weight'], null, $model, $det1_id);
            $this->setOutputIdToInputOutputDet1Table($det1Output->id);
            //UPDATE THE AMOUNT IN LAKE
            $det1->update(['weight' => $det1['weight'] - $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($det1->warehouse_id);
            $warehouse->update(['tot_weight' => $warehouse['tot_weight'] - $_detail['weight']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $result = [];
            if ($outputChoice == 'تخزين') {
                $result = $this->inputFromDet1ToStore($det1Output, $warehouse->id);
            } 
            return (["status" => true, "message" => "تم الإدخال بنجاح"]);
        }

    }

    public function insertNewRowInInputOutputDet1Table($input_id, $weight, $amount){
        $inputOutputDet1 = new DetonatorFrige1InputOutput();
        $inputOutputDet1->input_id = $input_id;
        $inputOutputDet1->weight = $weight;
        $inputOutputDet1->amount = $amount;
        $inputOutputDet1->save();
        return $inputOutputDet1->id;
    }

    public function insertNewRowInOutputDet1Table($weight, $amount, $model, $det1_id){
        $det1Output = new DetonatorFrige1Output();
        $det1Output->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $det1Output->outputable_type = $model;
        $det1Output->outputable_id = 0;
        $det1Output->weight = $weight;
        $det1Output->amount = $amount;
        $det1Output->det1_id = $det1_id;
        $det1Output->save();
        return $det1Output;
    }

    public function setOutputIdToInputOutputDet1Table($det1OutputId){
        $inputOutputDet1Elements = DetonatorFrige1InputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputDet1Elements as $_inputOutput) {
            $_inputOutput->update(['output_id' => $det1OutputId]);
        }
        return true;
    }

    public function inputFromDet1ToStore($det1Output, $warehouseId){
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $store = Store::where('warehouse_id', $warehouseId)->get();
        $storeDetail = new StoreDetail();
        $storeDetail->store_id = $store[0]->id;
        $storeDetail->weight = $det1Output->weight;
        $storeDetail->amount = $det1Output->amount;
        $storeDetail->cur_weight = $det1Output->weight;
        $storeDetail->cur_amount = $det1Output->amount;
        $storeDetail->inputable_type = 'App\Models\DetonatorFrige1Output';
        $storeDetail->inputable_id = $det1Output->id;
        $storeDetail->input_from = 'مستودع الصاعقة 1';
        $storeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $det1Output->outputable()->associate($storeDetail);
        $det1Output->save();
        
        $store[0]->update([
            'weight' => $store[0]->weight + $det1Output->weight,
            'amount' => $store[0]->amount + $det1Output->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $det1Output->weight,
            'tot_amount' => $warehouse->tot_amount + $det1Output->amount
        ]);

        return (["status" => true, "output" => $det1Output]);
        
    }

    /////////////////////////// DET2 /////////////////////////
    
    public function outputWeightFromDet2($_detail, $outputChoice){
        $det2_id = $_detail['det_id'];
        $det2 = DetonatorFrige2::find($det2_id);
        if (is_null($det2->weight) ||  $det2->weight < $_detail['weight'])
            return (["status" => false, "message" => "لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInDetonatorDetails = DetonatorFrige2Detail::where([['detonator_frige_2_id', $det2_id], ['cur_weight', '!=', 0]])->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInDetonatorDetails as $_det2_detail) {
            if ($tot_weight != 0) {
                if ($tot_weight >= $_det2_detail['cur_weight']) {
                    $tot_weight -= $_det2_detail['cur_weight'];
                    $_det2_detail->update(['cur_weight' => 0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputDet2Table($_det2_detail['id'], $_det2_detail['weight'], null);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_det2_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                } else {

                    $_det2_detail->update(['cur_weight' => $_det2_detail['cur_weight'] - $tot_weight]);
                    $tot_weight = 0;
                    $flag = true;
                    break;
                }
            } else {
                $flag = true;
                break;
            }
        }

        if ($tot_weight != 0) {
            return (["status" => false, "message" => $tot_weight]);

        } else {
            $model = $this->getModel($outputChoice);

            //ENOUGH AMOUNT
            $det2Output = $this->insertNewRowInOutputDet2Table($_detail['weight'], null, $model, $det2_id);
            $this->setOutputIdToInputOutputDet2Table($det2Output->id);
            //UPDATE THE AMOUNT IN LAKE
            $det2->update(['weight' => $det2['weight'] - $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($det2->warehouse_id);
            $warehouse->update(['tot_weight' => $warehouse['tot_weight'] - $_detail['weight']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $result = [];
            if ($outputChoice == 'تخزين') {
               $result =  $this->inputFromDet2ToStore($det2Output, $warehouse->id);
            } 
            return (["status" => true, "message" => "تم الإدخال بنجاح"]);
        }

    }

    public function insertNewRowInInputOutputDet2Table($input_id, $weight, $amount){
        $inputOutputDet2 = new DetonatorFrige2InputOutput();
        $inputOutputDet2->input_id = $input_id;
        $inputOutputDet2->weight = $weight;
        $inputOutputDet2->amount = $amount;
        $inputOutputDet2->save();
        return $inputOutputDet2->id;
    }

    public function insertNewRowInOutputDet2Table($weight, $amount, $model, $det2_id){
        $det2Output = new DetonatorFrige2Output();
        $det2Output->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $det2Output->outputable_type = $model;
        $det2Output->outputable_id = 0;
        $det2Output->weight = $weight;
        $det2Output->amount = $amount;
        $det2Output->det2_id = $det2_id;
        $det2Output->save();
        return $det2Output;
    }

    public function setOutputIdToInputOutputDet2Table($det2OutputId){
        $inputOutputDet2Elements = DetonatorFrige2InputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputDet2Elements as $_inputOutput) {
            $_inputOutput->update(['output_id' => $det2OutputId]);
        }
        return true;
    }

    public function inputFromDet2ToStore($det2Output, $warehouseId){
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $store = Store::where('warehouse_id', $warehouseId)->get();
        $storeDetail = new StoreDetail();
        $storeDetail->store_id = $store[0]->id;
        $storeDetail->weight = $det2Output->weight;
        $storeDetail->amount = $det2Output->amount;
        $storeDetail->cur_weight = $det2Output->weight;
        $storeDetail->cur_amount = $det2Output->amount;
        $storeDetail->inputable_type = 'App\Models\DetonatorFrige2Output';
        $storeDetail->inputable_id = $det2Output->id;
        $storeDetail->input_from = 'مستودع الصاعقة 2';
        $storeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $det2Output->outputable()->associate($storeDetail);
        $det2Output->save();
        
        $store[0]->update([
            'weight' => $store[0]->weight + $det2Output->weight,
            'amount' => $store[0]->amount + $det2Output->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $det2Output->weight,
            'tot_amount' => $warehouse->tot_amount + $det2Output->amount
        ]);

        return (["status" => true, "output" => $det2Output]);
        
    }

    /////////////////////// DET3 /////////////////////////////////////

    public function outputWeightFromDet3($_detail, $outputChoice){
        $det3_id = $_detail['det_id'];
        $det3 = DetonatorFrige3::find($det3_id);
        if (is_null($det3->weight) ||  $det3->weight < $_detail['weight'])
            return (["status" => false, "message" => "لا يوجد وزن كافي في المخزن"]);

        $flag = false;
        $elementsInDetonatorDetails = DetonatorFrige3Detail::where([['detonator_frige_3_id', $det3_id], ['cur_weight', '!=', 0]])->orderBy('created_at', 'DESC')->get();
        $tot_weight = $_detail['weight'];
        foreach ($elementsInDetonatorDetails as $_det3_detail) {
            if ($tot_weight != 0) {
                if ($_detail['weight'] >= $_det3_detail['cur_weight'] && $_det3_detail['cur_weight'] != 0) {
                    $tot_weight -= $_det3_detail['cur_weight'];
                    $_det3_detail->update(['cur_weight' => 0]);

                    //INSERT THIS ROW IN NEW INPUT_OUTPUT_LAKE TABLE
                    $this->insertNewRowInInputOutputDet3Table($_det3_detail['id'], $_det3_detail['weight'], null);

                    //UPDATE OUTBUT DATE IN LAKE DETAIL TABLE
                    $_det3_detail->update(['date_of_destruction' => Carbon::today()->format('Y-m-d H:i')]);
                } elseif($_det3_detail['cur_weight'] != 0) {

                    $_det3_detail->update(['cur_weight' => $_det3_detail['cur_weight'] - $tot_weight]);
                    $tot_weight = 0;
                    $flag = true;
                    break;
                }
            } else {
                $flag = true;
                break;
            }
        }

        if ($tot_weight != 0) {
            return (["status" => false, "message" => $tot_weight]);

        } else {
            $model = $this->getModel($outputChoice);

            //ENOUGH AMOUNT
            $det3Output = $this->insertNewRowInOutputDet3Table($_detail['weight'], null, $model, $det3_id);
            $this->setOutputIdToInputOutputDet3Table($det3Output->id);
            //UPDATE THE AMOUNT IN LAKE
            $det3->update(['weight' => $det3['weight'] - $_detail['weight']]);
            //UPDATE THE AMOUNT IN WAREHOUSE  
            $warehouse = Warehouse::find($det3->warehouse_id);
            $warehouse->update(['tot_weight' => $warehouse['tot_weight'] - $_detail['weight']]);
            //USE $lakeOutput WHEN INSERT INPUT IN ZERO FRIGE:
            /**
             * now the input from lake to zero
             */
            $result = [];
            if ($outputChoice == 'تخزين') {
                $result = $this->inputFromDet3ToStore($det3Output, $warehouse->id);
            } 
            return (["status" => true, "message" => "تم الإدخال بنجاح"]);
        }

    }

    public function insertNewRowInInputOutputDet3Table($input_id, $weight, $amount){
        $inputOutputDet3 = new DetonatorFrige3InputOutput();
        $inputOutputDet3->input_id = $input_id;
        $inputOutputDet3->weight = $weight;
        $inputOutputDet3->amount = $amount;
        $inputOutputDet3->save();
        return $inputOutputDet3->id;
    }

    public function insertNewRowInOutputDet3Table($weight, $amount, $model, $det3_id){
        $det3Output = new DetonatorFrige3Output();
        $det3Output->output_date = Carbon::today()->format('Y-m-d H:i:s');
        $det3Output->outputable_type = $model;
        $det3Output->outputable_id = 0;
        $det3Output->weight = $weight;
        $det3Output->amount = $amount;
        $det3Output->det3_id = $det3_id;
        $det3Output->save();
        return $det3Output;
    }

    public function setOutputIdToInputOutputDet3Table($det3OutputId){
        $inputOutputDet3Elements = DetonatorFrige3InputOutput::where([['output_id', null], ['created_at', Carbon::now()]])->get();
        foreach ($inputOutputDet3Elements as $_inputOutput) {
            $_inputOutput->update(['output_id' => $det3OutputId]);
        }
        return true;
    }

    public function inputFromDet3ToStore($det3Output, $warehouseId){
        //INSERT NEW ROW IN ZERO DETAIL FRIGE
        $store = Store::where('warehouse_id', $warehouseId)->get();
        $storeDetail = new StoreDetail();
        $storeDetail->store_id = $store[0]->id;
        $storeDetail->weight = $det3Output->weight;
        $storeDetail->amount = $det3Output->amount;
        $storeDetail->cur_weight = $det3Output->weight;
        $storeDetail->cur_amount = $det3Output->amount;
        $storeDetail->inputable_type = 'App\Models\DetonatorFrige3Output';
        $storeDetail->inputable_id = $det3Output->id;
        $storeDetail->input_from = 'مستودع الصاعقة 3';
        $storeDetail->save();

        //UPDATE THE ONPUTABLE IN LAKE OUTPUT TABLE
        $det3Output->outputable()->associate($storeDetail);
        $det3Output->save();
        
        $store[0]->update([
            'weight' => $store[0]->weight + $det3Output->weight,
            'amount' => $store[0]->amount + $det3Output->amount
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse = Warehouse::find($warehouseId);
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $det3Output->weight,
            'tot_amount' => $warehouse->tot_amount + $det3Output->amount
        ]);

        return (["status" => true, "output" => $det3Output]);
        
    }

    public function fillCommand($_detail, $from){
        $command_detail_id = $_detail['command_detail_id'];
        $weight = $_detail['weight'];
        
        $command_detail = CommandDetail::find($command_detail_id);
        if($_detail['weight']> $command_detail->command_weight)
            return (["status" => false, "message" =>"الوزن المدخل أكبر من الموجود"]);

        $to = $command_detail['to'];
        $warehouse = Warehouse::find($command_detail->warehouse_id);
        $result = [];
        if($from=='براد صفري'){
            $model = 'App\Models\ZeroFrigeOutput';
            $zeroFrige = ZeroFrige::where('warehouse_id', $warehouse->id)->get()->first();

            $zeroFrigeInfo['zero_id'] = $zeroFrige->id;
            $zeroFrigeInfo['weight'] = $weight;

            $result = $this->outputWeightFromZero($zeroFrigeInfo, $to);                 
        }

        else if($from=='بحرات'){
            $model = 'App\Models\LakeDetail';
            $lake = Lake::where('warehouse_id', $warehouse->id)->get()->first();

            $lakeInfo['lake_id'] = $lake->id;
            $lakeInfo['weight'] = $weight;

            $result = $this->outputWeightFromLake($lakeInfo, $to);
        }
        ///////////// UPDATE THE COMMANDS AND ITS DETAILS AND RETURN THE MESSAGE
        if($result['status']==true){
            //new fille commad row and the follible id = zero frige id | type id zeroFrige
            $fillCommand = new FillCommand();
            $fillCommand->command_id = $command_detail->command_id;
            $fillCommand->input_weight = $weight;
            $fillCommand->fillable_id = $result['message']['output']['id'];
            $fillCommand->fillable_type = $model;
            $fillCommand->save();
            //UPDATE COMMAND DETAILS CUR_WEIGHT
            $command_detail->update(['cur_weight'=>$command_detail->cur_weight + $weight, 'from'=>$from]);
            //// UPDATE THE COMMAND CUR_WEIGHT
            $command = Command::find($command_detail->command_id);
            $command->update(['cur_weight'=>$command->cur_weight + $weight]);

        }
        else if($result['status']==false)
            return (["status" => false, "message" =>$result['message']]);
        return (["status" => true, "message" =>$result['message']]);
    }

    public function checkIsCommandDone($commandId){
        $flag = true;
        $commandDetails = CommandDetail::where('command_id', $commandId)->get();
        foreach ($commandDetails as $_command) {
            if($_command->command_weight != $_command->cur_weight){
                $flag = false;
                break;
            }
            
        }
        if($flag == true){
            $command = Command::find($commandId)->update(['done'=>1]);
            return (["status" => true, "message" =>"تم انتهاء عمليات إخراج المواد من المخزن إلى الإنتاج"]);
        }
            return (["status" => false, "message" =>"لم يتم إنهاء عملية الإخراج بعد"]);
        
    } 

}    


