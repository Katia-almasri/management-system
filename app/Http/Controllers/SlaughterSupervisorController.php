<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\systemServices\warehouseServices;
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
use App\Models\Output_remnat;
use App\Models\Output_remnat_details;
use App\Models\Remnat;
use App\Models\RemnatDetail;

class SlaughterSupervisorController extends Controller
{
    use validationTrait;
    protected $warehouseService;

    public function __construct()
    {
        $this->warehouseService  = new warehouseServices();
    }
    public function displayInputSlaughters(request $request){
        $InputSlaughters = input_slaughter_table::where('output_date',null)->get();
        return response()->json($InputSlaughters, 200);
    }

    // public function changeStateInput(Request $request){
    //     input_slaughter_table::where('output_id',null)->update(['status' => 'يتم الذبح']);
    //     return response()->json(["status"=>true, "message"=>"يتم ذبح الشحنة"]);
    // }


    public function displayOutputDetTotalWeight(Request $request){
        $typeOutput = DB::table('output_slaughtersupervisors_details')
        ->join('output_production_types', 'output_slaughtersupervisors_details.type_id', '=', 'output_production_types.id')
        ->select('output_slaughtersupervisors_details.type_id','output_production_types.type', DB::raw('SUM(weight) as weight'))
        ->where([['direct_to_bahra',0]])->groupBy('type_id','output_production_types.type')->get();
        return response()->json($typeOutput, 200);
    }

    public function commandDirectToBahra(Request $request){

        $outPut_SlaughterSupervisor_detail = outPut_SlaughterSupervisor_detail::where('direct_to_bahra',0)->get();
        foreach ($outPut_SlaughterSupervisor_detail as $_outputDetail) {
            $type_id = $_outputDetail->type_id;
            //SEARCH IN WAREHOUSE
            $warehouse = Warehouse::where('type_id', $type_id)->get()->first();
            $this->warehouseService->storeNewInLake($warehouse->id, $_outputDetail->id);
        }
        outPut_SlaughterSupervisor_detail::where('direct_to_bahra',0)->update(['direct_to_bahra'=>1]);
        return response()->json(["status"=>true, "message"=>"تم التوجيه الى البحرات"]);
    }

    public function addOutputSlaughters(Request $request){
            // $InputSlaughters = input_slaughter_table::where('output_date',null)->sum('weight');
            // return response($InputSlaughters);
        try{

            $output = new outPut_SlaughterSupervisor_table();
            $output -> production_date = Carbon::now();
            $output ->save();
            $findInput = input_slaughter_table::where('output_date' , null)
            ->update([
                'output_id' => $output->id,
                'output_date' => Carbon::now()
            ]);

            $totalWeightProduction = 0;
            foreach($request->details as $_detail){
                $outputDetail = new outPut_SlaughterSupervisor_detail();
                $outputDetail->weight = $_detail['weight'];
                $outputDetail->type_id = $_detail['type_id'];
                $outputDetail->output_id = $output->id;
                $totalWeightProduction += $_detail['weight'];
                $outputDetail->save();
            }
            $outPut_SlaughterSupervisor_detail = outPut_SlaughterSupervisor_detail::where('direct_to_bahra',0)->get();
            foreach ($outPut_SlaughterSupervisor_detail as $_outputDetail) {
                $type_id = $_outputDetail->type_id;
                //SEARCH IN WAREHOUSE
                $warehouse = Warehouse::where('type_id', $type_id)->get()->first();
                $this->warehouseService->storeNewInLake($warehouse->id, $_outputDetail->id);
            }
            outPut_SlaughterSupervisor_detail::where('direct_to_bahra',0)->update(['direct_to_bahra'=>1]);

            ///////////////////////New
            $totalWeightRemnat = 0;
            if($request->details_remnat !=null){
                foreach($request->details_remnat as $_details_remnat){
                    $outputRemnatDetail = new Output_remnat_details();
                    $outputRemnatDetail->weight = $_details_remnat['weight'];
                    $outputRemnatDetail->type_remant_id = $_details_remnat['type_remant_id'];
                    $outputRemnatDetail->output_slaughter_id = $output->id;
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
            $InputSlaughters = input_slaughter_table::where('output_id',$output->id)->get();
            $totalWeightInput = 0;
            foreach($InputSlaughters as $_InputSlaughters){
                $totalWeightInput += $_InputSlaughters->weight;
            }
            $wastage = $totalWeightInput - ($totalWeightProduction + $totalWeightRemnat);
            outPut_SlaughterSupervisor_table::where('id',$output->id)->update(['wastage'=>$wastage]);


        return response()->json(["status"=>true, "message"=>"تم اضافة خرج"]);
    }catch (\Exception $exception) {
        DB::rollback();
        return response()->json(["status" => false, "message" => $exception->getMessage()]);
    }

    }

    public function displayOutputTypesSlaughter(Request $request){
        $types = outPut_Type_Production::where('by_section','قسم الذبح')->get(['id','type'] );
        return response()->json($types, 200);
    }

    public function displayOutputSlaughter(Request $request){
        $output = outPut_SlaughterSupervisor_detail::with('productionTypeOutPut')->orderBy('id', 'DESC')
        ->orderby('id','desc')->get();
        return response()->json($output, 200);
    }
}
