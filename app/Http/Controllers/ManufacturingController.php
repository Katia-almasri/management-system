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

use Carbon\Carbon;

use Auth;

class ManufacturingController extends Controller
{

    use validationTrait;

    public function displayInputManufacturing(Request $request){
        $input = InputManufacturing::get();
        return response()->json($input, 200);
    }

    public function ManufacturingIsDone(Request $request){
        foreach($request->ids as $_id){
            $findInputManufacturing = InputManufacturing::where([['id',$_id],['manufacturings_done',null]])->update(['manufacturings_done'=> 0]);
        }
        return response()->json(["status"=>true, "message"=>"تم ارسال الدخل إلى التصنيع"]);
    }

    public function displayInputManufacturingTotalWeight(Request $request){
        $typeInput = DB::table('input_manufacturings')
        ->join('output_slaughtersupervisors_details', 'input_manufacturings.output_slaughter_det_Id', '=', 'output_slaughtersupervisors_details.id')
        ->join('output_production_types', 'output_slaughtersupervisors_details.type_id', '=', 'output_production_types.id')
        ->select('output_slaughtersupervisors_details.type_id','output_production_types.type', DB::raw('SUM(input_manufacturings.weight) as weight'))
        ->where([['manufacturings_done',0],['output_date',null]])->groupBy('type_id','output_production_types.type')->get();
        return response()->json($typeInput, 200);
    }


    public function addOutputManufacturing(Request $request , $type_id){
        $output = new OutputManufacturing();
        $output -> production_date = Carbon::now();
        // $output -> waste_value = $request ->waste_value;
        $output ->save();
        $findInput = InputManufacturing::where([['type_id',$type_id],['manufacturings_done',0]])
        ->update(['output_manufacturing_id'=> $output->id]);

        foreach($request->details as $_detail){
            $outputDetail = new OutputManufacturingDetails();
            $outputDetail->weight = $_detail['weight'];
            $outputDetail->type_id = $_detail['type_id'];
            $outputDetail->output_manufacturing_id  = $output->id;
            $daysToAdd = outPut_SlaughterSupervisorType_table::where('id',$_detail['type_id'])
            ->pluck('number_day_validity')->first();
            $date = $output -> production_date;
            $outputDetail->expiry_date = $date->addDays($daysToAdd);
            $outputDetail->save();
            $findInputSlaughters = InputManufacturing::where([['manufacturings_done',0],['type_id',$type_id]])->update(['manufacturings_done'=> 1]);
        }
    return response()->json(["status"=>true, "message"=>"تم اضافة خرج"]);


}}

