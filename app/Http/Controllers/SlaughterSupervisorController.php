<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Warehouse;
use App\systemServices\notificationServices;
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
use App\Models\OutputManufacturingDetails;

class SlaughterSupervisorController extends Controller
{
    use validationTrait;
    protected $warehouseService;
    protected $notificationService;

    public function __construct()
    {
        $this->warehouseService  = new warehouseServices();
        $this->notificationService  = new notificationServices();
    }
    public function displayInputSlaughters(request $request){
        $InputSlaughters = input_slaughter_table::where('output_date',null)
        ->orderBy('created_at', 'DESC')->get();
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
            DB::beginTransaction();
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
            $notification = null;
            if($wastage!=0 && $wastage > 0.05* $totalWeightInput){
               $notification =$wastage ." تجاوز الفقد الحد الأدنى بمقدار";

            }
            //notification to warehouse supervisor because its now in lakes
            $data = $this->notificationService->makeNotification(
                'warehouse-channel',
                'App\\Events\\warehouseNotification',
                'وصل خرج الذبح إلى البحرات',
                '',
                $request->user()->id,
                '',
                0,
                'مشرف الذبح',
                ''
            );
            $this->notificationService->warehouNotification($data);

            $data = $this->notificationService->makeNotification(
                'production-channel',
                'App\\Events\\productionNotification',
                'وصل خرج الذبح إلى البحرات',
                '',
                $request->user()->id,
                '',
                0,
                'مشرف الذبح',
                ''
            );
            $this->notificationService->productionNotification($data);
            DB::commit();
        return response()->json(["status"=>true, "message"=>"تم اضافة خرج", "notification"=>$notification]);
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

    public function displayOutputRemnatSlaughter(Request $request){
        $outputRemnatSlaughter = Output_remnat_details::with('type_remnat')->where('output_slaughter_id','!=',null)->get();
        return response()->json($outputRemnatSlaughter, 200);
    }

    /////////////// NOTIFICATION PART ///////////////////
    public function displayReachedInputToSlaughter(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'slaughter-channel'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayReachedInputToSlaughterChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'slaughter-channel'],
            ['is_seen', '=', 0],
        ])->orderBy('created_at', 'DESC')->get();

        Notification::where([
            ['channel', '=', 'slaughter-channel'],
            ['is_seen', '=', 0],
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);


    }


    ///////////////////////////////dashboard///////////////////////
    public function CountTypeProductionSlaughter(Request $request){
        $CountTypeSlaughter = outPut_Type_Production::where('by_section','قسم الذبح')->get()->count();
        return response()->json($CountTypeSlaughter, 200);
    }

    public function chartInputSlaughter(Request $request){
        $inputSlaughter = input_slaughter_table::select(DB::raw("SUM(weight) as sum"), DB::raw("MONTHNAME(created_at) as month_name"))
        ->whereYear('created_at', date('Y'))
        ->groupBy(DB::raw("month_name"))
        ->orderBy('id','Desc')
        ->pluck('sum', 'month_name');
        $labels = $inputSlaughter->keys();
        $data = $inputSlaughter->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


    public function chartOutputSlaughterThisMonth(Request $request){
        $outputSlaughter = outPut_SlaughterSupervisor_detail::select(DB::raw("SUM(weight) as sum"), DB::raw("type as type_name"))
        ->join('output_production_types','output_production_types.id','=','output_slaughtersupervisors_details.type_id')
        ->whereYear('output_slaughtersupervisors_details.created_at', date('Y'))
        ->whereMonth('output_slaughtersupervisors_details.created_at', date('m'))
        ->groupBy(DB::raw("type_name"))
        ->orderBy('output_slaughtersupervisors_details.id','Desc')
        ->pluck('sum', 'type_name');
        $labels = $outputSlaughter->keys();
        $data = $outputSlaughter->values();
        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }


}
