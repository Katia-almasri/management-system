<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\InputCutting;
use App\Models\output_cutting;
use App\Models\output_cutting_detail;
use App\Models\outPut_SlaughterSupervisorType_table;

use Carbon\Carbon;

use Auth;

class CuttingController extends Controller
{

    use validationTrait;

    public function displayInputCutting(Request $request){
        $input = InputCutting::get();
        return response()->json($input, 200);
    }

    public function cutting_is_done(Request $request){
        foreach($request->ids as $_id){
            $findInputCutting = InputCutting::where([['id',$_id],['cutting_done',null]])->update(['cutting_done'=> 0]);
        }
        return response()->json(["status"=>true, "message"=>"تم ارسال الدخل إلى التقطيع"]);
    }

    public function displayInputCuttingTotalWeight(Request $request){
        $typeInput = DB::table('input_cuttings')
        ->join('output_slaughtersupervisors_details', 'input_cuttings.output_slaughter_det_Id', '=', 'output_slaughtersupervisors_details.id')
        ->join('output_production_types', 'output_slaughtersupervisors_details.type_id', '=', 'output_production_types.id')
        ->select('output_slaughtersupervisors_details.type_id','output_production_types.type', DB::raw('SUM(input_cuttings.weight) as weight'))
        ->where([['cutting_done',0],['output_date',null]])->groupBy('type_id','output_production_types.type')->get();
        return response()->json($typeInput, 200);
    }


    public function addOutputCutting(Request $request , $type_id){
        $output = new output_cutting();
        $output -> production_date = Carbon::now();
        // $output -> waste_value = $request ->waste_value;
        $output ->save();
        $findInput = InputCutting::where([['type_id',$type_id],['cutting_done',0]])
        ->update(['output_citting_id'=> $output->id]);

        foreach($request->details as $_detail){
            $outputDetail = new output_cutting_detail();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_cutting_id  = $output->id;
            $daysToAdd = outPut_SlaughterSupervisorType_table::where('id',$_detail['type_id'])
            ->pluck('number_day_validity')->first();
            $date = $output -> production_date;
            $outputDetail->expiry_date = $date->addDays($daysToAdd);
            $outputDetail->save();
            $findInputSlaughters = InputCutting::where([['cutting_done',0],['type_id',$type_id]])->update(['cutting_done'=> 1]);
        }
    return response()->json(["status"=>true, "message"=>"تم اضافة خرج"]);


}}

