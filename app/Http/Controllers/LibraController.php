<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\systemServices\notificationServices;
use Illuminate\Http\Request;
use App\Http\Requests\PoultryRecieptDetectionRequest;
use App\Http\Requests\WeightAfterArrivalRequest;
use App\Models\PoultryReceiptDetection;
use App\Models\PoultryReceiptDetectionsDetails;
// use App\Models\Trip;

use App\systemServices\poultryDetectionRequestServices;
use App\systemServices\weightAfterArrivalServices;
use App\Exceptions\Exception;
use Illuminate\Support\Facades\DB;

class LibraController extends Controller
{

    protected $poultryDetectionRequestService;
    protected $weightAfterArrivalService;
    protected $notificationService;

    public function __construct()
    {
        $this->poultryDetectionRequestService = new poultryDetectionRequestServices();
        $this->weightAfterArrivalService = new weightAfterArrivalServices();
        $this->notificationService = new notificationServices();

    }

    public function addPoultryRecieptDetection(PoultryRecieptDetectionRequest $request, $trip_id)
    {
        try {
            $finalResult = $this->poultryDetectionRequestService->storePoultryDetectionRequest($request, $trip_id);
            if ($finalResult['status'] == true){
                Trip::where('id', $trip_id)->update(['status'=>'تم الاستلام']);
                return ["status" => true, "message" => "تم إضافة الكشف بنجاح"];
            }
                
            else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }

    public function addWeightAfterArrivalDetection(WeightAfterArrivalRequest $request, $recieptId)
    {
        try {
            //وزن السحنة بعد الوصول فقط(وزن كلي و ووزن فارغ)
            $finalResult = $this->weightAfterArrivalService->weightAfterArrive($request, $recieptId);
            $recieptWeighted = PoultryReceiptDetection::where('id', $recieptId)->update(['is_weighted_after_arrive'=>1]);
            if ($finalResult['status'] == true){
                 //إرسال إشعار للمدير التنفيذي بوصول الشحنة ووزنها
                $data['type'] = 'وزن الشحنة بعد وصولها';
                $data['reciept_id'] = $recieptId;
                $this->notificationService->addWeightRecieptAfterArriveNotif($data);
                // ////////////////// SEND THE NOTIFICATION /////////////////////////


                return ["status" => true, "message" => "  تم وزن الشحنة بعد وصولها بنجاح وارسالها لقسم الذبح"];
            }
            else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function getReciepts(Request $request)
    {
        $PoultryReceiptDetections = PoultryReceiptDetection::with([
            'PoultryReceiptDetectionDetails.rowMaterial','farm'
        ])->orderBy('id', 'DESC')->get();
        return response()->json($PoultryReceiptDetections);
    }

    public function getRecieptInfo(Request $request, $recieptId)
    {
        $poultryRecieptDetection = PoultryReceiptDetection::with([
            'PoultryReceiptDetectionDetails.rowMaterial','farm'
        ])->where('id', '=', $recieptId)->get();
        return response()->json($poultryRecieptDetection);
    }


}
