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
use App\Models\outPut_Type_Production;


class SlaughterSupervisorController extends Controller
{
    use validationTrait;

    public function displayInputSlaughters(request $request){
        $InputSlaughters = input_slaughter_table::where([['output_date',null],['status',null]])->get();
        return response()->json($InputSlaughters, 200);
    }

    public function changeStateInput(Request $request){
        input_slaughter_table::where('output_id',null)->update(['status' => 'يتم الذبح']);
        return response()->json(["status"=>true, "message"=>"يتم ذبح الشحنة"]);
    }


    // public function displayInputTotalWeight(Request $request){
    //     $typeInput = DB::table('input_slaughters')
    //     ->join('row_materials', 'input_slaughters.type_id', '=', 'row_materials.id')
    //     ->select('input_slaughters.type_id','row_materials.name', DB::raw('SUM(weight) as weight'))
    //     ->where([['slaughter_done',0],['output_date',null]])->groupBy('type_id','row_materials.name')->get();
    //     return response()->json($typeInput, 200);
    // }

    public function addOutputSlaughters(Request $request){
            $output = new outPut_SlaughterSupervisor_table();
            $output -> production_date = Carbon::now();
            $output ->save();
            $findInput = input_slaughter_table::where('status' , 'يتم الذبح')

            ->update([
                'output_id' => $output->id,
                'status' => 'تم انهاء الذبح',
                'output_date' => Carbon::now()
            ]);

            foreach($request->details as $_detail){
                $outputDetail = new outPut_SlaughterSupervisor_detail();
                $outputDetail->weight = $_detail['weight'];
                $outputDetail->CurrentWeight = $_detail['weight'];
                $outputDetail->type_id = $_detail['type_id'];
                $outputDetail->output_id = $output->id;
                $outputDetail->save();
                // $findInputSlaughters = input_slaughter_table::where('status' , 'يتم الذبح')->update(['status'=> 'تم انهاء الذبح']);
            }
            // return response()->json($findInput, 200);
        return response()->json(["status"=>true, "message"=>"تم اضافة خرج"]);

    }


    public function displayOutputTypes(Request $request){
        $types = outPut_Type_Production::where('by_section','قسم الذبح')->get(['id','type'] );
        return response()->json($types, 200);
    }

    public function displayOutputSlaughter(Request $request){
        $output = outPut_SlaughterSupervisor_table::with('detail_output_slaughter')->orderBy('id', 'DESC')->get();
        return response()->json($output, 200);
    }
}
