<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\InputManufacturing;
use App\Models\OutputManufacturing;
use App\Models\output_cutting_detail;
use App\Models\OutputManufacturingDetails;
use App\systemServices\productionServices;
use App\Models\outPut_Type_Production;
use App\Models\Output_remnat_details;
use App\Models\Remnat;
use App\Models\RemnatDetail;

use Carbon\Carbon;

use Auth;

class ManufacturingController extends Controller
{

    use validationTrait;

    protected $productionService;

    public function __construct()
    {
        $this->productionService  = new productionServices();
    }

    public function displayInputManufacturing(Request $request)
    {
        $input = InputManufacturing::with('output_types')->get();
        return response()->json($input, 200);
    }

    // public function ManufacturingIsDone(Request $request)
    // {
    //     foreach ($request->ids as $_id) {
    //         $findInputManufacturing = InputManufacturing::where([['id', $_id], ['manufacturings_done', null]])->update(['manufacturings_done' => 0]);
    //     }
    //     return response()->json(["status" => true, "message" => "تم ارسال الدخل إلى التصنيع"]);
    // }

    public function displayInputManufacturingTotalWeight(Request $request)
    {
        $typeInput = DB::table('input_manufacturings')
        ->join('output_production_types', 'input_manufacturings.type_id', '=', 'output_production_types.id')
        ->select('input_manufacturings.type_id','output_production_types.type', DB::raw('SUM(weight) as weight'))
        ->where('output_manufacturing_id',null)->groupBy('type_id','output_production_types.type')->get();
        return response()->json($typeInput, 200);
    }


    public function addOutputManufacturing(Request $request, $type_id)
    {
        $output = new OutputManufacturing();
        $output->production_date = $request->production_date;
        $output->save();
        $findInput = InputManufacturing::where([['type_id', $type_id],['output_manufacturing_id',null]])
            ->update(['output_manufacturing_id' => $output->id]);

        $totalWeightProduction = 0;
        foreach ($request->details as $_detail) {
            $outputDetail = new OutputManufacturingDetails();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_manufacturing_id = $output->id;
            $outputDetail->outputable_type = '';
            $outputDetail->outputable_id = 0;
            $totalWeightProduction += $_detail['weight'];
            $outputDetail->save();

        }
        $totalWeightRemnat = 0;
        if($request->details_remnat !=null){
            foreach($request->details_remnat as $_details_remnat){
                $outputRemnatDetail = new Output_remnat_details();
                $outputRemnatDetail->weight = $_details_remnat['weight'];
                $outputRemnatDetail->type_remant_id = $_details_remnat['type_remant_id'];
                $outputRemnatDetail->output_manufacturing_id = $output->id;
                $totalWeightRemnat += $_details_remnat['weight'];
                $outputRemnatDetail->save();

                $remnatDetail = new RemnatDetail();
                $remnatType = Remnat::where('type_remant_id',$_details_remnat['type_remant_id'])->get()->first();

                if($remnatType == null){

                    $remnat = new Remnat();
                    $remnat->type_remant_id = $_details_remnat['type_remant_id'];
                    $remnat->weight = $_details_remnat['weight'];
                    $remnat->save();

                    $remnatDetail->remant_id = $remnat->id;
                }
                else{
                    $weightRemnat =0;
                    $findRemnat =  Remnat::where('type_remant_id',$_details_remnat['type_remant_id'])->get()->first();
                    $weightRemnat = $findRemnat->weight + $_details_remnat['weight'];
                    $findRemnat->update(['weight'=>$weightRemnat]);
                    $remnatDetail->remant_id = $findRemnat->id;
                }


                $remnatDetail->weight = $_details_remnat['weight'];
                $remnatDetail->output_remnat_det_id = $outputRemnatDetail->id;
                //remant_id
                $remnatDetail->save();
            }
        }

        $totalWeight = $totalWeightProduction + $totalWeightRemnat;
            $InputManufacturing = InputManufacturing::where('output_manufacturing_id',$output->id)->get();
            $totalWeightInput = 0;
            foreach($InputManufacturing as $_InputManufacturing){
                $totalWeightInput += $_InputManufacturing->weight;
            }
            $wastage = $totalWeightInput - ($totalWeightProduction + $totalWeightRemnat);
            OutputManufacturing::where('id',$output->id)->update(['wastage'=>$wastage]);

            $notification = null;
            if($wastage!=0 && $wastage > 0.05* $totalWeightInput){    
               $notification =$wastage ." تجاوز الفقد الحد الأدنى بمقدار";

            }

        return response()->json(["status" => true, "message" => "تم اضافة خرج", "notification"=>$notification]);


    }

    public function displayOutputManufacturing(Request $request)
    {
        $output = OutputManufacturing::with('detail_output_manufacturing.outputTypes')->orderBy('id', 'DESC')->get();
        return response()->json($output, 200);
    }

    public function directManufactoringTo(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->productionService->outputWeightFromCManufactoring($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);

            }
            DB::commit();
            return response()->json(["status" => true, "message" => $result['message']]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }


    public function displayOutputTypeManufacturing(Request $request){
        $type = outPut_Type_Production::where('by_section','قسم التصنيع')->get();
        return response()->json($type, 200);
    }

    public function displayManufacturingOutputWhereNotOutputable(Request $request){
        $output = OutputManufacturingDetails::with('outputTypes')->where('weight','!=',0)->get();
        return response()->json($output, 200);
    }

    public function displayOutputRemnatmanufacturing(Request $request){
        $outputRemnatmanufacturing = Output_remnat_details::with('type_remnat')->where('output_manufacturing_id','!=',null)->get();
        return response()->json($outputRemnatmanufacturing, 200);
    }


    ///////////////////////////////dashboard///////////////////////
    public function CountTypeProductionManufacturing(Request $request){
        $CountTypeManufacturing = outPut_Type_Production::where('by_section','قسم التصنيع')->get()->count();
        return response()->json($CountTypeManufacturing, 200);
    }


    public function chartInputManufacturingThisMonth(Request $request){
        $inputManufacturing = InputManufacturing::select(DB::raw("SUM(weight) as sum"), DB::raw("type as type_name"))
        ->join('output_production_types','output_production_types.id','=','input_manufacturings.type_id')
        ->whereYear('input_manufacturings.created_at', date('Y'))
        ->whereMonth('input_manufacturings.created_at', date('m'))
        ->groupBy(DB::raw("type_name"))
        ->orderBy('input_manufacturings.id','Desc')
        ->pluck('sum', 'type_name');
        $labels = $inputManufacturing->keys();
        $data = $inputManufacturing->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }



    public function chartOutputManufacturingThisMonth(Request $request){
        $outputManufacturing = OutputManufacturingDetails::select(DB::raw("SUM(weight) as sum"), DB::raw("type as type_name"))
        ->join('output_production_types','output_production_types.id','=','output_manufacturing_details.type_id')
        ->whereYear('output_manufacturing_details.created_at', date('Y'))
        ->whereMonth('output_manufacturing_details.created_at', date('m'))
        ->groupBy(DB::raw("type_name"))
        ->orderBy('output_manufacturing_details.id','Desc')
        ->pluck('sum', 'type_name');
        $labels = $outputManufacturing->keys();
        $data = $outputManufacturing->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
