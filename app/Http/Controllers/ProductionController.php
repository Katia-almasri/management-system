<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\weightAfterArrivalDetectionDetail;
use App\Models\InputProduction;
use App\Models\typeChicken;
use App\Models\input_slaughter_table;
use App\Models\outPut_SlaughterSupervisorType_table;
use App\Models\outPut_input_slaughter;



use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class ProductionController extends Controller
{
    use validationTrait;

    public function displayLibraCommanderOutPut(Request $request){
        $commander = weightAfterArrivalDetectionDetail::where('approved_at' , null)
        ->with(['PoultryReceiptDetectionsDetails' => function($query){
            $query->with('rowMaterial');
        }])->get();
        return response()->json($commander, 200);
    }

    public function approveCommanderDetail(Request $request, $detailCommandId){
        $validator = Validator::make($request->all(), [
            'weight' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $name = DB::table('after_arrival_detection_details')
        ->join('poultry_receipt_detections_details', 'poultry_receipt_detections_details.id', '=', 'after_arrival_detection_details.details_id')
        ->join('row_materials', 'poultry_receipt_detections_details.row_material_id', '=', 'row_materials.id')
        ->where([['after_arrival_detection_details.id' , '=' , $detailCommandId],['approved_at' , null]])
        ->pluck('row_materials.name')->first();
        $typeChicken = typeChicken::where('type' , $name)->pluck('id')->first();;
        $findWeightAfter = weightAfterArrivalDetectionDetail::find($detailCommandId);
        if($request->weight <= $findWeightAfter->current_weight){
            $Input = new InputProduction();
            $Input -> weight = $request->weight;
            $s =$findWeightAfter->current_weight-= $request->weight;
            $findWeightAfterUpdate = weightAfterArrivalDetectionDetail::where('id',$detailCommandId)
            ->update(['current_weight'=>$s]);
            if($findWeightAfter->current_weight == 0)
                $findWeightAfterUpdate = weightAfterArrivalDetectionDetail::where('id',$detailCommandId)
            ->update(['approved_at'=>Carbon::now()->toDateTimeString()]);
            $Input -> weight_detail_id = $detailCommandId;
            $Input -> income_date = Carbon::now()->toDateTimeString();
            $Input -> type_id = $typeChicken;
            $Input ->save();
            return  response()->json(["status"=>true, "message"=>"تم تأكيد استلام المادة من الشحنة"]);
        }
        else{
            return  response()->json(["status"=>false, "message"=>"الوزن المدخل أكبر من الوزن الموجود عند امر القبان"]);
        }


    }

    public function displayInputProduction(Request $request){
        $inputProduction = InputProduction::where('output_date' , null)->get();
        return response()->json($inputProduction, 200);
    }

    public function CommandSlaughterSupervisor(Request $request){
        $InputProduction = InputProduction::where([['CommandSlaughterSupervisor' ,null],['output_date',null]])
        ->get();
        foreach ($InputProduction as $_as ) {
            $inputSlaughterSupervisor = new input_slaughter_table();
            $inputSlaughterSupervisor->weight = $_as->weight;
            $inputSlaughterSupervisor->type_id = $_as->type_id;
            $inputSlaughterSupervisor->productionId = $_as->id;
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



}
