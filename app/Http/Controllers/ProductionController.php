<?php

namespace App\Http\Controllers;

use App\Models\Command;
use App\Models\CommandDetail;
use App\Models\outPut_Type_Production;
use App\systemServices\warehouseServices;
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
use App\Models\outPut_SlaughterSupervisor_table;
use App\Models\output_cutting;
use App\Models\output_cutting_detail;
use App\Models\OutputManufacturing;
use App\Models\OutputManufacturingDetails;
use App\Models\Warehouse;



use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;

class ProductionController extends Controller
{
    use validationTrait;

    protected $warehouseService;

    public function __construct()
    {
        $this->warehouseService  = new warehouseServices();
    }
    public function displayLibraCommanderOutPut(Request $request){
        // $commander =weightAfterArrivalDetection::
        // with(['poltryDetection.PoultryReceiptDetectionDetails.rowMaterial'=>function($q){
        //     $q->select('id','name');
        // }])->get();

        $outputDetLibra = weightAfterArrivalDetection::where('approved_at' , null)->get();

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
            'by_section'=>'required',
            'num_expiration_days'=>'numeric|gte:0',

        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $type = new outPut_Type_Production();
        $type -> type = $request->type;
        $type -> by_section = $request->by_section;
        if($request->num_expiration_days!=null)
            $type -> num_expiration_days = $request->num_expiration_days;
        $type -> save();
        ///////////ADD THE NEW TYPE IN WAREHOUSE
        $this->warehouseService->addNewTypeInWarehouse($type->id);
        return  response()->json(["status"=>true, "message"=>"تم إضافة نوع جديد لخرج الانتاج "]);
    }

    public function displayTypeProductionOutPut(Request $request){
        $outPutProduction = outPut_Type_Production::all();
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
    /////////////////////// command from warehouse to production ///////////////////

    //إضافة أمر من مجير الإنتاج إلى المخازن
    public function addCommandToWarehouse(Request $request){
        $to = $request['to'];
        try {
            DB::beginTransaction();
            $command = new Command();
            $command->Done = 0;
            $command->save();
            foreach($request['details'] as $_detail){
                $warehouse_id = $_detail['warheouse_id'];
                $command_detail = new CommandDetail();
                $command_detail->warehouse_id = $warehouse_id;
                $command_detail->command_id = $command->id;
                $command_detail->cur_weight = 0;
                $command_detail->input_weight = 0;
                $command_detail->command_weight = $_detail['weight'];
                $command_detail->from = '';
                $command_detail->to = $request['outputChoice'];
                $command_detail->save();
            }
            DB::commit();
            return response()->json(["status" => true, "message" => "تمت إضافة الأمر بنجاح"]);
    } catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }

    }

    public function dropDownProducationManager(Request $request){
        return response()->json(["التقطيع", "تصنيع"]);
    }

    public function dropDownProducationManagerBySection(Request $request){
        return response()->json(['قسم الذبح', 'قسم التقطيع', 'قسم التصنيع']);
    }

    public function displayCommandsToWarehouse(Request $request){
        $commands = Command::with('commandDetails.warehouse.outPut_Type_Production')
        ->orderby('created_at','desc')
        ->get();
        return response()->json($commands, 200);
    }

    public function displayWarehouse(Request $request){
        $warehouse = Warehouse::with('outPut_Type_Production')->get();
        return response()->json($warehouse, 200);
    }

    public function displayCommandsWarehousToProduction(Request $request){
        $command = Command::with('commandDetails')->get();
        return response()->json($command, 200);
    }


    public function chartInputProduction(Request $request){
        $inputProduction = input_slaughter_table::select(DB::raw("SUM(weight) as sum"), DB::raw("MONTHNAME(created_at) as month_name"))
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('id','Desc')
        ->pluck('sum', 'month_name');

        $labels = $inputProduction->keys();
        $data = $inputProduction->values();
        return response()->json([
        'labels' => $labels,
        'data' => $data,
        ]);
    }


    public function chartOutputSlaughter(Request $request){
        $outputSlaughter = outPut_SlaughterSupervisor_table::select(DB::raw("SUM(weight) as sum"), DB::raw("MONTHNAME(output_slaughtersupervisors.created_at) as month_name"))
        ->join('output_slaughtersupervisors_details','output_slaughtersupervisors_details.output_id','=','output_slaughtersupervisors.id')
        ->whereYear('output_slaughtersupervisors.created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('output_slaughtersupervisors.id','Desc')
        ->pluck('sum', 'month_name');
        $labels = $outputSlaughter->keys();
        $data = $outputSlaughter->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


    public function chartOutputCutting(Request $request){
        $outputCutting = output_cutting::select(DB::raw("SUM(weight) as sum"), DB::raw("MONTHNAME(output_cuttings.created_at) as month_name"))
        ->join('output_cutting_details','output_cutting_details.output_cutting_id','=','output_cuttings.id')
        ->whereYear('output_cuttings.created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('output_cuttings.id','Desc')
        ->pluck('sum', 'month_name');
        $labels = $outputCutting->keys();
        $data = $outputCutting->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    public function chartOutputManufacturing(Request $request){
        $outputManufacturing = OutputManufacturing::select(DB::raw("SUM(weight) as sum"), DB::raw("MONTHNAME(output_manufacturings.created_at) as month_name"))
        ->join('output_manufacturing_details','output_manufacturing_details.output_manufacturing_id','=','output_manufacturings.id')
        ->whereYear('output_manufacturings.created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('output_manufacturings.id','Desc')
        ->pluck('sum', 'month_name');
        //  return response()->json($outputManufacturing, 200);
        $labels = $outputManufacturing->keys();
        $data = $outputManufacturing->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


    public function theBestOfProductProduction(Request $request){
        $PerfectProductofCutting = output_cutting_detail::select(DB::raw("type") , DB::raw("SUM(weight) as sum"))
        ->join('output_production_types','output_cutting_details.type_id','=','output_production_types.id')
        ->whereMonth('output_cutting_details.created_at', Carbon::now()->month)
        ->groupBy('type')
        ->orderBy('sum','desc')
        ->limit(5)->pluck('sum','type');

        $PerfectProductofManufacturing = OutputManufacturingDetails::select(DB::raw("type") , DB::raw("SUM(weight) as sum"))
        ->join('output_production_types','output_manufacturing_details.type_id','=','output_production_types.id')
        ->whereMonth('output_manufacturing_details.created_at', Carbon::now()->month)
        ->groupBy('type')
        ->orderBy('sum','desc')
        ->limit(5)->pluck('sum','type');

        $PerfectProductofSlaughter = outPut_SlaughterSupervisor_detail::select(DB::raw("type") , DB::raw("SUM(weight) as sum"))
        ->join('output_production_types','output_slaughtersupervisors_details.type_id','=','output_production_types.id')
        ->whereMonth('output_slaughtersupervisors_details.created_at', Carbon::now()->month)
        ->groupBy('type')
        ->orderBy('sum','desc')
        ->limit(5)->pluck('sum','type');


        $max = array($PerfectProductofCutting,$PerfectProductofManufacturing,$PerfectProductofSlaughter);
        return response()->json($max, 200);

        $labels = $PerfectProductofSlaughter->keys();
        $data = $PerfectProductofSlaughter->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
