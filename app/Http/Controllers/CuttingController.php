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

        foreach ($request->details as $_detail) {
            $outputDetail = new output_cutting_detail();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_cutting_id = $output->id;
            $outputDetail->outputable_id = 0;
            $outputDetail->outputable_type = '';
            $outputDetail->save();

        }
        return response()->json(["status" => true, "message" => "تم اضافة خرج"]);


    }
    public function displayOutputCutting(Request $request)
    {
        $output = output_cutting::with('detail_output_cutiing.outputTypes')->orderBy('id', 'DESC')->get();
        return response()->json($output, 200);
    }
    /////////////////////////////////// katia //////////////////////
    public function directCuttingTo(Request $request)
    {
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->productionService->outputWeightFromCutting($_detail, $request['outputChoice']);
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

    public function displayTypeCuttingOutput(Request $request){
        $types = outPut_Type_Production::where('by_section','قسم التقطيع')->get();
        return response()->json($types, 200);
    }


    public function displayCuttingOutputWhereNotOutputable(Request $request){
        $output = output_cutting_detail::with('outputTypes')->where('weight','!=',0)->get();
        return response()->json($output, 200);
    }
}
