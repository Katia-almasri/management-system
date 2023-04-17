<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PoultryRecieptDetectionRequest;
use App\Http\Requests\WeightAfterArrivalRequest;
use App\Models\PoultryReceiptDetection;
use App\Models\PoultryReceiptDetectionsDetails;
use App\systemServices\poultryDetectionRequestServices;
use App\systemServices\weightAfterArrivalServices;
use App\Exceptions\Exception;
use Illuminate\Support\Facades\DB;

class LibraController extends Controller
{

    protected $poultryDetectionRequestService;
    protected $weightAfterArrivalService;

    public function __construct()
    {
        $this->poultryDetectionRequestService = new poultryDetectionRequestServices();
        $this->weightAfterArrivalService = new weightAfterArrivalServices();

    }

    public function addPoultryRecieptDetection(PoultryRecieptDetectionRequest $request)
    {
        try {
            $finalResult = $this->poultryDetectionRequestService->storePoultryDetectionRequest($request);
            if ($finalResult['status'] == true)
                return ["status" => true, "message" => "تم إضافة الكشف بنجاح"];
            else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }

    public function addWeightAfterArrivalDetection(WeightAfterArrivalRequest $request, $recieptId)
    {
        try {
            $finalResult = $this->weightAfterArrivalService->storeWeightAfterArrivalRequest($request, $recieptId);
            if ($finalResult['status'] == true)
                return ["status" => true, "message" => "تم وزن الشحنة بعد وصولها بنجاح"];
            else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function getReciepts(Request $request){
        $PoultryReceiptDetections = PoultryReceiptDetection::get();
        return response()->json($PoultryReceiptDetections);
    }

    public function getRecieptInfo(Request $request, $recieptId){
        $poultryRecieptDetection = PoultryReceiptDetection::with(['PoultryReceiptDetectionDetails'=> function($q){
            $q->with('rowMaterial');
        }])->where('id', '=', $recieptId)->get();                       
        return response()->json($poultryRecieptDetection);
    }

    public function getWeightAfterArrival(Request $request, $recieptId){
        $weightAfterArrivalDetection = PoultryReceiptDetection::where('id', $recieptId)
                                       ->with(['PoultryReceiptDetectionDetails',
                                               'weightAfterArrivalDetection'=>function ($q){
                                                    $q->with('weightAfterArrivalDetectionDetail');
                                        }])
                                        ->get();
        return response()->json($weightAfterArrivalDetection);   
    }

}