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
        $input = InputManufacturing::get();
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

        foreach ($request->details as $_detail) {
            $outputDetail = new OutputManufacturingDetails();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_manufacturing_id = $output->id;
            $outputDetail->outputable_type = '';
            $outputDetail->outputable_id = 0;
            $outputDetail->save();
            
        }
        return response()->json(["status" => true, "message" => "تم اضافة خرج"]);


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

}