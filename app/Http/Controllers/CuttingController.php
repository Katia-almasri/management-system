<?php

namespace App\Http\Controllers;

use App\Models\outPut_Type_Production;
use App\systemServices\productionServices;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\InputCutting;
use App\Models\output_cutting;
use App\Models\output_cutting_detail;
use App\Models\Output_remnat_details;
use App\Models\Remnat;
use App\Models\RemnatDetail;

use Carbon\Carbon;

use Auth;

class CuttingController extends Controller
{

    use validationTrait;

    protected $productionService;

    public function __construct()
    {
        $this->productionService  = new productionServices();
    }

    public function displayInputCutting(Request $request)
    {
        $input = InputCutting::with('output_types')->get();
        return response()->json($input, 200);
    }

    // public function cutting_is_done(Request $request)
    // {
    //     foreach ($request->ids as $_id) {
    //         $findInputCutting = InputCutting::where([['id', $_id], ['cutting_done', null]])->update(['cutting_done' => 0]);
    //     }
    //     return response()->json(["status" => true, "message" => "تم ارسال الدخل إلى التقطيع"]);
    // }

    public function displayInputCuttingTotalWeight(Request $request)
    {
        $typeInput = DB::table('input_cuttings')
        ->join('output_production_types', 'input_cuttings.type_id', '=', 'output_production_types.id')
        ->select('input_cuttings.type_id','output_production_types.type', DB::raw('SUM(weight) as weight'))
        ->where('output_citting_id',null)->groupBy('type_id','output_production_types.type')->get();
        return response()->json($typeInput, 200);
    }


    public function addOutputCutting(Request $request, $type_id)
    {
        $output = new output_cutting();
        // $output->production_date = $request->production_date;
        $output->save();
        $findInput = InputCutting::where([['type_id', $type_id],['output_citting_id',null]])
            ->update(['output_citting_id' => $output->id]);

            $totalWeightProduction = 0;
        foreach ($request->details as $_detail) {
            $outputDetail = new output_cutting_detail();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_cutting_id = $output->id;
            $outputDetail->outputable_id = 0;
            $outputDetail->outputable_type = '';
            $totalWeightProduction += $_detail['weight'];
            $outputDetail->save();

        }

        $totalWeightRemnat = 0;
        if($request->details_remnat !=null){
            foreach($request->details_remnat as $_details_remnat){
                $outputRemnatDetail = new Output_remnat_details();
                $outputRemnatDetail->weight = $_details_remnat['weight'];
                $outputRemnatDetail->type_remant_id = $_details_remnat['type_remant_id'];
                $outputRemnatDetail->output_cutting_id  = $output->id;
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
            $InputCutting = InputCutting::where('output_citting_id',$output->id)->get();
            $totalWeightInput = 0;
            foreach($InputCutting as $_InputCutting){
                $totalWeightInput += $_InputCutting->weight;
            }
            $wastage = $totalWeightInput - ($totalWeightProduction + $totalWeightRemnat);
            output_cutting::where('id',$output->id)->update(['wastage'=>$wastage]);

            $notification = null;
            if($wastage!=0 && $wastage > 0.05* $totalWeightInput){    
               $notification =$wastage ." تجاوز الفقد الحد الأدنى بمقدار";

            }


        return response()->json(["status" => true, "message" => "تم اضافة خرج", "notification"=>$notification]);


    }
    public function displayOutputCutting(Request $request)
    {
        $output = output_cutting::with('detail_output_cutiing.outputTypes')->orderBy('id', 'DESC')->get();
        return response()->json($output, 200);
    }
    /////////////////////////////////// katia //////////////////////
    public function directCuttingTo(Request $request)
    {
        // try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->productionService->outputWeightFromCutting($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);

            }
            DB::commit();
            return response()->json(["status" => true, "message" => $result['message']]);
        // } catch (\Exception $exception) {
        //     DB::rollback();
        //     return response()->json(["status" => false, "message" => $exception->getMessage()]);
        // }
    }

    public function displayTypeCuttingOutput(Request $request){
        $types = outPut_Type_Production::where('by_section','قسم التقطيع')->get();
        return response()->json($types, 200);
    }


    public function displayCuttingOutputWhereNotOutputable(Request $request){
        $output = output_cutting_detail::with('outputTypes')->where('weight','!=',0)->get();
        return response()->json($output, 200);
    }

    public function displayOutputRemnatCutting(Request $request){
        $outputRemnatCutting = Output_remnat_details::with('type_remnat')->where('output_cutting_id','!=',null)->get();
        return response()->json($outputRemnatCutting, 200);
    }


    ///////////////////////////////dashboard///////////////////////
    public function CountTypeProductionCutting(Request $request){
        $CountTypeCutting = outPut_Type_Production::where('by_section','قسم التقطيع')->get()->count();
        return response()->json($CountTypeCutting, 200);
    }


    public function chartInputCuttingThisMonth(Request $request){
        $inputCutting = InputCutting::select(DB::raw("SUM(weight) as sum"), DB::raw("type as type_name"))
        ->join('output_production_types','output_production_types.id','=','input_cuttings.type_id')
        ->whereYear('input_cuttings.created_at', date('Y'))
        ->whereMonth('input_cuttings.created_at', date('m'))
        ->groupBy(DB::raw("type_name"))
        ->orderBy('input_cuttings.id','Desc')
        ->pluck('sum', 'type_name');
        $labels = $inputCutting->keys();
        $data = $inputCutting->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }



    public function chartOutputCuttingThisMonth(Request $request){
        $outputCutting = output_cutting_detail::select(DB::raw("SUM(weight) as sum"), DB::raw("type as type_name"))
        ->join('output_production_types','output_production_types.id','=','output_cutting_details.type_id')
        ->whereYear('output_cutting_details.created_at', date('Y'))
        ->whereMonth('output_cutting_details.created_at', date('m'))
        ->groupBy(DB::raw("type_name"))
        ->orderBy('output_cutting_details.id','Desc')
        ->pluck('sum', 'type_name');
        $labels = $outputCutting->keys();
        $data = $outputCutting->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
