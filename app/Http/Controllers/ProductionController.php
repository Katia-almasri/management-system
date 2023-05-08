<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\weightAfterArrivalDetection;
use App\Models\InputProduction;
use App\Models\typeChicken;
use App\Models\input_slaughter_table;
use App\Models\outPut_SlaughterSupervisorType_table;
use App\Models\outPut_input_slaughter;
use App\Models\outPut_SlaughterSupervisor_detail;
use App\Models\InputCutting;
use App\Models\InputManufacturing;
use App\Models\DirectToOutputSlaughter;



use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class ProductionController extends Controller
{
    use validationTrait;

    public function displayLibraCommanderOutPut(Request $request){
        // $commander = weightAfterArrivalDetection::where('approved_at' , null)
        // ->with(['poltryDetection' => function($query){
        //     $query->with(['PoultryReceiptDetectionDetails'=> function($query1){
        //         $query1->with('rowMaterial');
        //     }]);
        // }])->get();

        $outputDetLibra = DB::table(
            'weight_after_arrival_detections'
        )
        ->where('approved_at' , null)->join(
            'poultry_receipt_detections',
            'weight_after_arrival_detections.polutry_detection_id', '=', 'poultry_receipt_detections.id'
        )->join(
            'poultry_receipt_detections_details',
            'poultry_receipt_detections_details.receipt_id', '=', 'poultry_receipt_detections.id'
        )->join(
            'row_materials',
            'poultry_receipt_detections_details.row_material_id', '=', 'row_materials.id'
        )
        ->select(['poultry_receipt_detections_details.id','poultry_receipt_detections_details.num_cages','poultry_receipt_detections_details.tot_weight',
        'poultry_receipt_detections_details.num_birds','poultry_receipt_detections_details.net_weight','name'])->get();

        return response()->json($outputDetLibra, 200);
    }

    // public function approveCommanderDetail(Request $request){
    //     // $validator = Validator::make($request->all(), [
    //     //     'weight' => 'required',
    //     // ]);

    //     // if($validator->fails()){
    //     //     return response()->json(['error' => $validator->errors()->all()]);
    //     // }

    //     foreach($request ->details_id as $_id){
    //         $id = $_id['id'];
    //         $Type_id = DB::table('weight_after_arrival_detections')
    //         ->join('poultry_receipt_detections_details', 'after_arrival_detection_details.details_id', '=', 'poultry_receipt_detections_details.id')
    //         ->where([['after_arrival_detection_details.id' , '=' , $id],['approved_at' , null]])
    //         ->pluck('poultry_receipt_detections_details.row_material_id')->first();
    //         $findWeightAfter = weightAfterArrivalDetectionDetail::find($id);
    //         $Input = new InputProduction();
    //         $Input -> weight = $_id['weight'];
    //         $Input -> type_id = $Type_id;
    //         $s = $findWeightAfter->current_weight -= $_id['weight'];
    //         $findWeightAfterUpdate = weightAfterArrivalDetectionDetail::where('id',$id)
    //         ->update(['current_weight'=>$s]);
    //         if($findWeightAfter->current_weight == 0)
    //             $findWeightAfterUpdate = weightAfterArrivalDetectionDetail::where('id',$id)
    //         ->update(['approved_at'=>Carbon::now()->toDateTimeString()]);
    //             $Input -> weight_detail_id = $id;
    //             $Input -> income_date = Carbon::now()->toDateTimeString();
    //             $Input ->save();
    //     }
    //     return  response()->json(["status"=>true, "message"=>"تم تأكيد استلام المادة من الشحنة"]);
    // }

    public function displayInputProduction(Request $request){
        $inputProduction = InputProduction::where([['output_date' , null],['CommandSlaughterSupervisor',null]])->get();
        return response()->json($inputProduction, 200);
    }

    public function CommandSlaughterSupervisor(Request $request){

        foreach ($request->ids as $_as ) {
            $InputProduction = InputProduction::find($_as['id']);
                $inputSlaughterSupervisor = new input_slaughter_table();
                $inputSlaughterSupervisor->weight = $InputProduction->weight;
                $inputSlaughterSupervisor->type_id = $InputProduction->type_id;
                $inputSlaughterSupervisor->productionId = $InputProduction->id;
                $inputSlaughterSupervisor->income_date = Carbon::now()->toDateTimeString();
                $inputSlaughterSupervisor->save();
                $update_details = array(
                    'output_date' =>  Carbon::now()->toDateTimeString(),
                    'CommandSlaughterSupervisor' => 1
                );
                $command = InputProduction::where([['CommandSlaughterSupervisor' ,null],['output_date',null]])
                ->update($update_details);
        }
        return  response()->json(["status"=>true, "message"=>"تم إعطاء امر التنفيذ لمشرف الذبح "]);
    }

    public function addTypeToProductionOutPut(Request $request){
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'number_day_validity' => 'required',

        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $type = new outPut_SlaughterSupervisorType_table();
        $type -> type = $request->type;
        $type -> number_day_validity = $request->number_day_validity;
        $type -> save();
        return  response()->json(["status"=>true, "message"=>"تم إضافة نوع جديد لخرج الانتاج "]);
    }

    public function displayTypeProductionOutPut(Request $request){
        $outPutProduction = outPut_SlaughterSupervisorType_table::all();
        return response()->json($outPutProduction, 200);
    }

    public function deleteFromProdctionOutPut(Request $request, $typeId){
        outPut_SlaughterSupervisorType_table::find($typeId)->delete();
        return  response()->json(["status"=>true, "message"=>"تم حذف  نوع من خرج الانتاج "]);
    }

    public function directTo(Request $request){
        foreach ($request->details as $_det){
            $id = $_det['id'];
            $outputDetails = outPut_SlaughterSupervisor_detail::find($id);
            $outputDetails -> CurrentWeight -= $_det['weight'];
            $outputDetails -> save();
            if($_det['direct_to'] == "قسم التقطيع"){
                $directTo = new DirectToOutputSlaughter();
                $directTo ->output_det_s_id = $_det['id'];
                $directTo ->weight = $_det['weight'];
                $directTo -> direct_to = $_det['direct_to'];
                $directTo->save();

                $inputCutting = new InputCutting();
                $inputCutting->weight = $directTo->weight;
                $inputCutting->type_id = $outputDetails->type_id;
                $inputCutting->income_date = Carbon::now()->toDateTimeString();
                $inputCutting->direct_to_output_slaughters_id = $directTo->id;
                $inputCutting->save();
            }
            else if($_det['direct_to'] == "قسم التصنيع"){
                $directTo = new DirectToOutputSlaughter();
                $directTo ->output_det_s_id = $_det['id'];
                $directTo ->weight = $_det['weight'];
                $directTo -> direct_to = $_det['direct_to'];
                $directTo->save();

                $inputManufacturing = new InputManufacturing();
                $inputManufacturing->weight = $directTo->weight;
                $inputManufacturing->type_id = $outputDetails->type_id;
                $inputManufacturing->income_date = Carbon::now()->toDateTimeString();
                $inputManufacturing->direct_to_output_slaughters_id = $directTo->id;;
                $inputManufacturing->save();
            }
        }
        return  response()->json(["status"=>true, "message"=>"تم توجيه الخرج الى القسم الجديد "]);
    }



}
