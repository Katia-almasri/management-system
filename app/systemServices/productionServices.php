<?php
namespace App\systemServices;

use App\Models\DetonatorFrige1;
use App\Models\DetonatorFrige1Detail;
use App\Models\DetonatorFrige2;
use App\Models\DetonatorFrige2Detail;
use App\Models\DetonatorFrige3;
use App\Models\DetonatorFrige3Detail;
use App\Models\InputManufacturing;
use App\Models\OutputManufacturingDetails;
use App\Models\output_cutting_detail;
use App\Models\Warehouse;
use App\Models\ZeroFrige;
use App\Models\ZeroFrigeDetail;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Carbon\Carbon;

class productionServices{

    ///////////////// CUTTINHG /////////////////////
    public function outputWeightFromCutting($_detail, $outputChoice){
        $output_cutting_detail_id = $_detail['output_cutting_detail_id'];
        $output_cutting_detail = output_cutting_detail::find($output_cutting_detail_id);
        $weight = $output_cutting_detail->weight;
        $type_id = $output_cutting_detail->type_id;

        //CHECK IF WEIGHT IS LESS THAN OR EQUALS WEIGHT IN THE DETAILS
        if($weight < $_detail['weight']){
            return (["status" => false, "message" => "الوزن المدخل أكبر من الموجود"]);
        }
        $output_cutting_detail->update(['weight'=>$weight - $_detail['weight']]);
        if($outputChoice=='براد صفري'){
            $this->insertInZeroFrigeDetail($type_id, $_detail['weight'], 'App\Models\output_cutting_detail', $output_cutting_detail);
        }
        else if($outputChoice=='تصنيع'){
            $this->insertInInputManufacturing($type_id, $_detail['weight'], 'App\Models\output_cutting_detail', $output_cutting_detail);
        }
        return (["status" => true, "message" => "تمت عملية الإخراج  بنجاح"]);
    }

    public function insertInZeroFrigeDetail($type_id, $weight, $model, $output_x_detail){
        $warehouse = Warehouse::where('type_id', $type_id)->get();
        $zeroFrige = ZeroFrige::where('warehouse_id', $warehouse[0]->id)->get()->first();
        /////////////// INSERT NEW ROW IN ZERO FRIGE DETAIL ///////////////////
        $zeroFrigeDetail = new ZeroFrigeDetail();
        $zeroFrigeDetail->zero_frige_id = $zeroFrige->id;
        $zeroFrigeDetail->weight = $weight;
        $zeroFrigeDetail->cur_weight = $weight;
        $zeroFrigeDetail->inputable_type = $model;
        $zeroFrigeDetail->inputable_id = $output_x_detail->id;
        $zeroFrigeDetail->input_from = 'الإنتاج';
        $zeroFrigeDetail->save();

        /////////////// UPDATE INPUTABLE ID AND TYPE IN output_cutting_detail
        $output_x_detail->outputable()->associate($zeroFrigeDetail);
        $output_x_detail->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $zeroFrige->update([
            'weight' => $zeroFrige->weight + $weight
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse[0]->update([
            'tot_weight' => $warehouse[0]->tot_weight + $weight
        ]);

    }

    public function insertInInputManufacturing($type_id, $weight, $model, $output_cutting_detail){
        $inputManufacturing = new InputManufacturing();
        $inputManufacturing->weight = $weight;
        $inputManufacturing->type_id = $type_id;
        $inputManufacturing->inputable_type = $model;
        $inputManufacturing->inputable_id = $output_cutting_detail->id;
        $inputManufacturing->input_from = 'الإنتاج';
        $inputManufacturing->save();

        $output_cutting_detail->outputable()->associate($inputManufacturing);
        $output_cutting_detail->save();
        //UPDATE output_cutting_detail VALUE
        // $output_cutting_detail->update(['weight'=>$output_cutting_detail->weight - $weight]);

    }

    //////////////// MANUFACTORING ////////////////////

    public function outputWeightFromCManufactoring($_detail, $outputChoice){
        $output_manufactoring_detail_id = $_detail['output_manufactoring_detail_id'];
        $outputManufactoringDetail = OutputManufacturingDetails::find($output_manufactoring_detail_id);
        $weight = $outputManufactoringDetail->weight;
        $type_id = $outputManufactoringDetail->type_id;

        //CHECK IF WEIGHT IS LESS THAN OR EQUALS WEIGHT IN THE DETAILS
        if($weight < $_detail['weight']){
            return (["status" => false, "message" => "الوزن المدخل أكبر من الموجود"]);
        }
        $outputManufactoringDetail->update(['weight'=>$weight - $_detail['weight']]);
        $model = '';
        if ($outputChoice == 'صاعقة 1') {
            $this->insertInDetonatorFrige1Detail($type_id, $_detail['weight'], 'App\Models\OutputManufacturingDetails', $outputManufactoringDetail);
        }
        else if ($outputChoice == 'صاعقة 2') {
            $this->insertInDetonatorFrige2Detail($type_id, $_detail['weight'], 'App\Models\OutputManufacturingDetails', $outputManufactoringDetail);
        }

        else if ($outputChoice == 'صاعقة 3') {
            $this->insertInDetonatorFrige3Detail($type_id, $_detail['weight'], 'App\Models\OutputManufacturingDetails', $outputManufactoringDetail);
        }

        else if ($outputChoice == 'براد صفري') {
            $this->insertInZeroFrigeDetail($type_id, $_detail['weight'], 'App\Models\OutputManufacturingDetails', $outputManufactoringDetail);
        }

        return (["status" => true, "message" => "تمت عملية الإخراج بنجاح "]);
    }

    public function insertInDetonatorFrige1Detail($type_id, $weight, $model, $outputManufactoringDetail){
        $warehouse = Warehouse::where('type_id', $type_id)->get()->first();
        $detonatorFrige1 = DetonatorFrige1::where('warehouse_id', $warehouse->id)->get()->first();
        /////////////// INSERT NEW ROW IN ZERO FRIGE DETAIL ///////////////////
        $detonatorFrige1Detail = new DetonatorFrige1Detail();
        $detonatorFrige1Detail->detonator_frige_1_id = $detonatorFrige1->id;
        $detonatorFrige1Detail->weight = $weight;
        $detonatorFrige1Detail->cur_weight = $weight;
        $detonatorFrige1Detail->inputable_type = $model;
        $detonatorFrige1Detail->inputable_id = $outputManufactoringDetail->id;
        $detonatorFrige1Detail->input_from = 'الإنتاج';
        $detonatorFrige1Detail->save();

        /////////////// UPDATE INPUTABLE ID AND TYPE IN outputManufactoringDetail
        $outputManufactoringDetail->outputable()->associate($detonatorFrige1Detail);
        $outputManufactoringDetail->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $detonatorFrige1->update([
            'weight' => $detonatorFrige1->weight + $weight
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $weight
        ]);

    }

    public function insertInDetonatorFrige2Detail($type_id, $weight, $model, $outputManufactoringDetail){
        $warehouse = Warehouse::where('type_id', $type_id)->get()->first();
        $detonatorFrige2 = DetonatorFrige2::where('warehouse_id', $warehouse->id)->get()->first();
        /////////////// INSERT NEW ROW IN ZERO FRIGE DETAIL ///////////////////
        $detonatorFrige2Detail = new DetonatorFrige2Detail();
        $detonatorFrige2Detail->detonator_frige_2_id = $detonatorFrige2->id;
        $detonatorFrige2Detail->weight = $weight;
        $detonatorFrige2Detail->cur_weight = $weight;
        $detonatorFrige2Detail->inputable_type = $model;
        $detonatorFrige2Detail->inputable_id = $outputManufactoringDetail->id;
        $detonatorFrige2Detail->input_from = 'الإنتاج';
        $detonatorFrige2Detail->save();

        /////////////// UPDATE INPUTABLE ID AND TYPE IN outputManufactoringDetail
        $outputManufactoringDetail->outputable()->associate($detonatorFrige2Detail);
        $outputManufactoringDetail->save();
        $detonatorFrige2->update([
            'weight' => $detonatorFrige2->weight + $weight
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $weight
        ]);
    }

    public function insertInDetonatorFrige3Detail($type_id, $weight, $model, $outputManufactoringDetail){
        $warehouse = Warehouse::where('type_id', $type_id)->get()->first();
        $detonatorFrige3 = DetonatorFrige3::where('warehouse_id', $warehouse->id)->get()->first();
        /////////////// INSERT NEW ROW IN ZERO FRIGE DETAIL ///////////////////
        $detonatorFrige3Detail = new DetonatorFrige3Detail();
        $detonatorFrige3Detail->detonator_frige_3_id = $detonatorFrige3->id;
        $detonatorFrige3Detail->weight = $weight;
        $detonatorFrige3Detail->cur_weight = $weight;
        $detonatorFrige3Detail->inputable_type = $model;
        $detonatorFrige3Detail->inputable_id = $outputManufactoringDetail->id;
        $detonatorFrige3Detail->input_from = 'الإنتاج';
        $detonatorFrige3Detail->save();

        /////////////// UPDATE INPUTABLE ID AND TYPE IN outputManufactoringDetail
        $outputManufactoringDetail->outputable()->associate($detonatorFrige3Detail);
        $outputManufactoringDetail->save();
        //UPDATE THE VALUE IN ZERO FRIGE
        $detonatorFrige3->update([
            'weight' => $detonatorFrige3->weight + $weight
        ]);
        //UPDATE THE VALUE IN WAREHOUSE
        $warehouse->update([
            'tot_weight' => $warehouse->tot_weight + $weight
        ]);
    }


}
