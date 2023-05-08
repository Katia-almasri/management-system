<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
Use \Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use Auth;
use App\Models\input_slaughter_table;
use App\Models\outPut_SlaughterSupervisor_table;
use App\Models\outPut_SlaughterSupervisor_detail;
use App\Models\outPut_SlaughterSupervisorType_table;


class SlaughterSupervisorController extends Controller
{
    use validationTrait;

    public function displayInputSlaughters(request $request){
        $InputSlaughters = input_slaughter_table::where([['output_date',null],['slaughter_done',null]])->get();
        return response()->json($InputSlaughters, 200);
    }

    public function changeStateInput(Request $request, $inputId){
        input_slaughter_table::where('id',$inputId)->update(['slaughter_done' => 0]);
        return response()->json(["status"=>true, "message"=>"يتم ذبح الشحنة"]);
    }



    public function displayInputTotalWeight(Request $request){
        $typeInput = DB::table('input_slaughters')
        ->join('row_materials', 'input_slaughters.type_id', '=', 'row_materials.id')
        ->select('input_slaughters.type_id','row_materials.name', DB::raw('SUM(weight) as weight'))
        ->where([['slaughter_done',0],['output_date',null]])->groupBy('type_id','row_materials.name')->get();
        return response()->json($typeInput, 200);
    }

    public function addOutputSlaughters(Request $request , $type_id){
            $output = new outPut_SlaughterSupervisor_table();
            $output -> production_date = Carbon::now();
            // $output -> waste_value = $request ->waste_value;
            $output ->save();
            $findInput = input_slaughter_table::where([['type_id',$type_id],['slaughter_done',0]])
            ->update(['output_id'=> $output->id]);

            foreach($request->details as $_detail){
                $outputDetail = new outPut_SlaughterSupervisor_detail();
                $outputDetail->weight = $_detail['weight'];
                $outputDetail->type_id = $_detail['type_id'];
                $outputDetail->output_id = $output->id;
                $daysToAdd = outPut_SlaughterSupervisorType_table::where('id',$_detail['type_id'])
                ->pluck('number_day_validity')->first();
                $date = $output -> production_date;
                $outputDetail->expiry_date = $date->addDays($daysToAdd);
                $outputDetail->save();
                $findInputSlaughters = input_slaughter_table::where([['slaughter_done',0],['type_id',$type_id]])->update(['slaughter_done'=> 1]);
            }
        return response()->json(["status"=>true, "message"=>"تم اضافة خرج"]);

    }

    public function processing_is_done(Request $request){
        foreach($request->ids as $_id){
            $findInputSlaughters = input_slaughter_table::where([['id',$_id],['slaughter_done',null]])->update(['slaughter_done'=> 0]);
        }
        return response()->json(["status"=>true, "message"=>"تم ارسال الدخل إلى الذبح"]);
    }

    public function displayOutputTypes(Request $request){
        $types = outPut_SlaughterSupervisorType_table::get();
        return response()->json($types, 200);
    }

    public function displayOutputSlaughter(Request $request){
        $output = outPut_SlaughterSupervisor_table::with('detail_output_slaughter')->orderBy('id', 'DESC')->get();
        return response()->json($output, 200);
    }
}
