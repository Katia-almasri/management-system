<?php
namespace App\systemServices;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\WeightAfterArrivalRequest;
use App\Models\PoultryReceiptDetection;
use App\Models\PoultryReceiptDetectionsDetails;
use App\Models\weightAfterArrivalDetection;
use App\Models\weightAfterArrivalDetectionDetail;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;

class weightAfterArrivalServices
{

    protected $num_birds = 10;
    protected $cage_weight = 40.0;

    public function storeWeightAfterArrivalRequest(WeightAfterArrivalRequest $request, $recieptId)
    {
        //1. CAGE WEIGHT(BEFOR) =< CAGE WEIGHT(AFTER)
        //2. NUM CAGES(BEFORE) != NUM CAGES(AFTER)
        //3. STORE THE WEIGHT AFTER ARRIVAL
        //4. STORE THE DETAILS
        $dead_chicken = 0;
        $tot_weight_after_arrival = 0.0;
        $counter = 0;

        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $weightAfterArrivalDetailsResult = $this->processWeightAfterArrivalDetails($_detail['detection_details'], $recieptId, $counter);
                $counter += 1;
                if ($weightAfterArrivalDetailsResult['status'] == true) {
                    $dead_chicken += $weightAfterArrivalDetailsResult['message']['dead_chicken'];
                    $tot_weight_after_arrival += $weightAfterArrivalDetailsResult['message']['tot_weight_after_arrival'];
                } else
                    throw new \ErrorException($weightAfterArrivalDetailsResult['message']);
            }

            $weight_loss = $this->calculateTotWeightAfterArrival($tot_weight_after_arrival, $recieptId);

            $polutryDetectionData = [
                "libra_commander_id" => $request->user()->id,
                "polutry_detection_id" => $recieptId,
                "dead_chicken" => $dead_chicken,
                "tot_weight_after_arrival" => $tot_weight_after_arrival,
                "weight_loss" => $weight_loss,
                "empty" => $weightAfterArrivalDetailsResult['message']['empty']
            ];
            //1. STORE THE WEIGHT AFTER ARRIVAL
            $finalResult = $this->storeWeightAfterArrival($polutryDetectionData);
            if ($finalResult['status'] == true) {
                //2. STORE THE DETAILS
                $finalResultDetails = $this->storeAfterWeightDetails($request, $finalResult['detectionId'], $recieptId);
                if ($finalResultDetails['status'] == true) {
                    $this->changeDetectionState($recieptId);
                    DB::commit();
                    return (["status" => true, "message" => $finalResultDetails['message']]);
                } else
                    throw new \ErrorException($finalResult['message']);
            } else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            DB::rollback();
            return (["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function processWeightAfterArrivalDetails($detection_details, $recieptId, $counter)
    {

        $num_cages = 0;
        $tot_material_weight = 0.0;
        $dead_chicken = 0;
        
        foreach ($detection_details as $_detail) {
            $num_cages += 1;
            $tot_material_weight += $_detail['cage_weight'];
            $dead_chicken += $_detail['dead_chicken'];
        }

        //COMPARE TO THE RECIEPT BEFOR ARRIVAL:
        $poultryRecieptDetection = PoultryReceiptDetection::with('PoultryReceiptDetectionDetails')
            ->where('id', '=', $recieptId)->get();

        if ($poultryRecieptDetection[0]->PoultryReceiptDetectionDetails[$counter]->num_cages != $num_cages) {
            return (["status" => false, "message" => "!عدد الأقفاص لا يساوي عدد أقفاص الكشف"]);
        }

        if ($poultryRecieptDetection[0]->PoultryReceiptDetectionDetails[$counter]->tot_weight < $tot_material_weight) {
            return (["status" => false, "message" => "!الوزن الكلي لهذا النوع أكبر الوزن الكلي في الكشف"]);
        }

        if ($poultryRecieptDetection[0]->PoultryReceiptDetectionDetails[$counter]->num_cages * $this->num_birds < $dead_chicken) {
            return (["status" => false, "message" => "خطأ في الإدخال"]);
        }
        
        $empty = $num_cages * $this->cage_weight;
        $net_weight_after_arrival = $tot_material_weight - $empty;
        $weight_loss = $poultryRecieptDetection[0]->PoultryReceiptDetectionDetails[$counter]->net_weight - $net_weight_after_arrival;

        $afterArrivalDetails = [
            "dead_chicken" => $dead_chicken,
            "tot_weight_after_arrival" => $tot_material_weight,
            "weight_loss" => $weight_loss,
            "net_weight_after_arrival" => $net_weight_after_arrival,
            "empty" => $empty
        ];

        return (["status" => true, "message" => $afterArrivalDetails]);
    }

    public function storeWeightAfterArrival($polutryDetectionData)
    {
        $weightAfterArrivalDetection = new weightAfterArrivalDetection();
        $weightAfterArrivalDetection->libra_commander_id = $polutryDetectionData['libra_commander_id'];
        $weightAfterArrivalDetection->polutry_detection_id = $polutryDetectionData['polutry_detection_id'];
        $weightAfterArrivalDetection->dead_chicken = $polutryDetectionData['dead_chicken'];
        $weightAfterArrivalDetection->tot_weight_after_arrival = $polutryDetectionData['tot_weight_after_arrival'];
        $weightAfterArrivalDetection->weight_loss = $polutryDetectionData['weight_loss'];

        $empty = $polutryDetectionData['empty'];
        $net_weight_after_arrival = $polutryDetectionData['tot_weight_after_arrival'] - $empty;
        $weightAfterArrivalDetection->net_weight_after_arrival = $net_weight_after_arrival;
        $weightAfterArrivalDetection->save();
        return ([
            "status" => true,
            "message" => "تم إضافة وزن بعد الشحنة بعد الوصول بنجاح",
            "detectionId" => $weightAfterArrivalDetection->id
        ]);

    }

    public function calculateTotWeightAfterArrival($tot_weight_after_arrival, $recieptId)
    {
        $poultryRecieptDetection = PoultryReceiptDetection::with('PoultryReceiptDetectionDetails')
            ->where('id', '=', $recieptId)->get();
        $tot_weight_before_arrival = 0.0;
        for ($i = 0; $i < count($poultryRecieptDetection[0]->PoultryReceiptDetectionDetails); $i++) {
            $tot_weight_before_arrival += $poultryRecieptDetection[0]->PoultryReceiptDetectionDetails[$i]->tot_weight;
        }
        $weight_loss = $tot_weight_before_arrival - $tot_weight_after_arrival;
        return ($weight_loss);

    }

    public function storeAfterWeightDetails(WeightAfterArrivalRequest $request, $detectionId, $recieptId)
    {
        $counter = 0;
        $PoultryReceiptDetectionDetails = $this->getPoultryReceiptDetectionDetails($recieptId);
        foreach ($request->details as $_detail) {
            $weightAfterArrivalDetectionDetail = new weightAfterArrivalDetectionDetail();
            $weightAfterArrivalDetectionDetail->detection_id = $detectionId;
            $weightAfterArrivalDetectionDetail->details_id = $PoultryReceiptDetectionDetails[$counter]->id;

            //CALCULATE (DEAD CHIKEN, TOT WEIGHT AFTER ARRIVAL)
            $DetailsDetection = $this->calculateDetailsDetection($_detail['detection_details']);

            //CALCULATE (LOSS, NET)
            $weight_loss = $PoultryReceiptDetectionDetails[$counter]->tot_weight - $DetailsDetection['tot_weight_after_arrival'];
            $empty = $PoultryReceiptDetectionDetails[$counter]->num_cages * $this->cage_weight;
            $net_weight_after_arrival = $DetailsDetection['tot_weight_after_arrival'] - $empty - $weight_loss;

            $weightAfterArrivalDetectionDetail->dead_chicken = $DetailsDetection['dead_chicken'];
            $weightAfterArrivalDetectionDetail->tot_weight_after_arrival = $DetailsDetection['tot_weight_after_arrival'];
            $weightAfterArrivalDetectionDetail->weight_loss = $weight_loss;
            $weightAfterArrivalDetectionDetail->net_weight_after_arrival = $net_weight_after_arrival;
            $weightAfterArrivalDetectionDetail->save();
            $counter += 1;
        }

        return ([
            "status" => true,
            "message" => "AfterWeightDetails successfully"
        ]);
    }

    public function getPoultryReceiptDetectionDetails($recieptId)
    {
        $poultryRecieptDetection = PoultryReceiptDetection::with('PoultryReceiptDetectionDetails')
            ->where('id', '=', $recieptId)->get();
        $PoultryReceiptDetectionDetails = $poultryRecieptDetection[0]->PoultryReceiptDetectionDetails;
        return ($PoultryReceiptDetectionDetails);

    }

    public function calculateDetailsDetection($detection_details)
    {
        $tot_weight_after_arrival = 0.0;
        $dead_chicken = 0;

        foreach ($detection_details as $_detail) {
            $tot_weight_after_arrival += $_detail['cage_weight'];
            $dead_chicken += $_detail['dead_chicken'];
        }

        return ([
            'tot_weight_after_arrival' => $tot_weight_after_arrival,
            'dead_chicken' => $dead_chicken
        ]);
    }

    public function changeDetectionState($recieptId){
        $poultryRecieptDetection = PoultryReceiptDetection::where('id', $recieptId)
                                   ->update(['is_weighted_after_arrive'=> 1]);
    }


}