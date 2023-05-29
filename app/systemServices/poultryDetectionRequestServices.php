<?php
namespace App\systemServices;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\PoultryRecieptDetectionRequest;
use App\Models\CageDetail;
use App\Models\PoultryReceiptDetection;
use App\Models\PoultryReceiptDetectionsDetails;
use App\Models\Trip;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;

class poultryDetectionRequestServices
{

    protected $num_birds = 10;
    protected $cage_weight = 8.5;

    public function storePoultryDetectionRequest(PoultryRecieptDetectionRequest $request, $trip_id)
    {
        //1. MANAGE THE CAGE DETAILS
        //2. MANAGE THE DETECTION DETAILS
        //3. MANAGE THE WHOLE DETECTION
        $tot_weight = 0.0;
        $num_cages = 0;
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->processDetectionDetails($_detail);
                $tot_weight += $result['tot_material_weight'];
                $num_cages += $result['num_cages'];
            }

            $empty = $num_cages * $this->cage_weight;
            $netWeight = $tot_weight - $empty;

            if ($netWeight < 0 || $netWeight > $tot_weight)  //tot_weight - net_weight if more than percentage (check)
                throw new \ErrorException('خطأ في الإدخال');
            //2. cage weight > num(cage_weight + max(bird_wieght)*num_bird)

            //1. STORE THE DETECTION
            $farm_id = Trip::where('id',$trip_id)->get()->pluck('farm_id')->first();
            $polutryDetectionData = [
                'farm_id' => $farm_id,
                'libra_commander_id' => $request->user()->id,
                'tot_weight' => $tot_weight,
                'empty' => $empty,
                'net_weight' => $netWeight,
                'trip_id' => $trip_id,
                'num_cages' => $num_cages
            ];

            $detectionResult = $this->storePoultryDetection($polutryDetectionData);
            if ($detectionResult['status'] == true) {

                //2. STORE THE DETECTION DETAIL
                $detectionDetailsResult = $this->storeDetectionDetail($request->details, $detectionResult['recieptId']);
                if ($detectionDetailsResult['status'] == true) {
                    // DONE
                    DB::commit();
                    return (["status" => true, "message" => $detectionResult['message']]);
                } else
                    throw new \ErrorException($detectionDetailsResult['message']);

            } else
                throw new \ErrorException($detectionResult['message']);



        } catch (\Exception $exception) {
            DB::rollback();
            return (["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function processCageDetails($detection_details)
    {
        $tot_material_weight = 0.0;
        $num_cages = 0;
        foreach ($detection_details as $_detail) {
            $tot_material_weight += $_detail['group_weight'];
            $num_cages += $_detail['num_cages'];
        }
        return (["tot_material_weight" => $tot_material_weight, "num_cages" => $num_cages]);
    }

    public function processDetectionDetails($details)
    {
        $result = $this->processCageDetails($details['detection_details']);
        $row_material_id = $details['row_material_id'];
        $detectionDetails['num_cages'] = $result['num_cages'];
        $detectionDetails['tot_material_weight'] = $result['tot_material_weight'];
        $detectionDetails['num_birds_per_material'] = $result['num_cages'] * $this->num_birds;
        return ($detectionDetails);
    }

    public function storePoultryDetection($polutryDetectionData)
    {
        $poultryRecieptDetection = new PoultryReceiptDetection();
        $poultryRecieptDetection->farm_id = $polutryDetectionData['farm_id'];
        $poultryRecieptDetection->libra_commander_id = $polutryDetectionData['libra_commander_id'];
        $poultryRecieptDetection->tot_weight = $polutryDetectionData['tot_weight'];
        $poultryRecieptDetection->empty = $polutryDetectionData['empty'];
        $poultryRecieptDetection->net_weight = $polutryDetectionData['net_weight'];
        $poultryRecieptDetection->num_cages = $polutryDetectionData['num_cages'];
        $poultryRecieptDetection->trip_id = $polutryDetectionData['trip_id'];
        $poultryRecieptDetection->save();
        return ([
            "status" => true,
            "message" => "detection successfully",
            "recieptId" => $poultryRecieptDetection->id
        ]);
    }

    public function storeDetectionDetail($detectionDetails, $recieptId)
    {
        foreach ($detectionDetails as $_detail) {
            $cageDetailsResult = $this->processCageDetails($_detail['detection_details']);
            $tot_material_weight = $cageDetailsResult['tot_material_weight'];
            $num_cages = $cageDetailsResult['num_cages'];

            $PoultryReceiptDetectionsDetails = new PoultryReceiptDetectionsDetails();
            $PoultryReceiptDetectionsDetails->receipt_id = $recieptId;
            $PoultryReceiptDetectionsDetails->row_material_id = $_detail['row_material_id'];
            $PoultryReceiptDetectionsDetails->num_cages = $num_cages;
            $PoultryReceiptDetectionsDetails->tot_weight = $tot_material_weight;
            // here
            $PoultryReceiptDetectionsDetails->num_birds = $this->num_birds * $num_cages;
            //here
            $PoultryReceiptDetectionsDetails->net_weight = $tot_material_weight - ($num_cages * $this->cage_weight);
            $PoultryReceiptDetectionsDetails->save();
        }
        return ([
            "status" => true,
            "message" => "detection details successfully",
        ]);

    }

}
