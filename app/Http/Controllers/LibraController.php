<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Truck;
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
            DB::beginTransaction();
            //وزن السحنة بعد الوصول فقط(وزن كلي و ووزن فارغ)
            $finalResult = $this->weightAfterArrivalService->weightAfterArrive($request, $recieptId);
            $recieptWeighted = PoultryReceiptDetection::where('id', $recieptId)->update(['is_weighted_after_arrive'=>1]);
            //get poultry reciept detection
            $poultry = PoultryReceiptDetection::find($recieptId);
            //get trip id
            $trip = Trip::find($poultry->trip_id);
            //get driver id and change state to availbavle
            $driver = Driver::where('id', $trip->driver_id)->update(['state'=>'متاح']);
            //get truck id and change state to availbavle
            $driver = Truck::where('id', $trip->truck_id)->update(['state'=>'متاحة']);
            if ($finalResult['status'] == true){
                // ////////////////// SEND THE NOTIFICATION /////////////////////////

                $data = $this->notificationService->makeNotification(
                    'slaughter-channel',
                    'App\\Events\\slaughterNotification',
                    'وزن الشحنة بعد وصولها',
                    '',
                    $request->user()->id,
                    '',
                    $recieptId,
                    'آمر القبان',
                    ''
                );

                $this->notificationService->slaughterNotification($data);

                $data = $this->notificationService->makeNotification(
                    'production-channel',
                    'App\\Events\\productionNotification',
                    'وزن الشحنة بعد وصولها',
                    '',
                    $request->user()->id,
                    '',
                    $recieptId,
                    'آمر القبان',
                    ''
                );

                $this->notificationService->productionNotification($data);
                DB::commit();

                return ["status" => true, "message" => "  تم وزن الشحنة بعد وصولها بنجاح وارسالها لقسم الذبح"];
            }
            else
                throw new \ErrorException($finalResult['message']);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function getReciepts(Request $request)
    {
        $PoultryReceiptDetections = PoultryReceiptDetection::with([
            'PoultryReceiptDetectionDetails.rowMaterial','farm'
        ])->orderBy('id', 'Asc')->get();
        return response()->json($PoultryReceiptDetections);
    }

    public function getRecieptInfo(Request $request, $recieptId)
    {
        $poultryRecieptDetection = PoultryReceiptDetection::with([
            'PoultryReceiptDetectionDetails.rowMaterial','farm'
        ])->where('id', '=', $recieptId)->get();
        return response()->json($poultryRecieptDetection);
    }

    ////////////////////////////     NOTIFICATION PART /////////////////////////
    public function displayDailtReportNotification(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'daily-libra-report-ready'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayDailtReportNotificationAndChangeState(Request $request){
        $notifications = Notification::where([
            ['channel', '=', 'daily-libra-report-ready'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'daily-libra-report-ready'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }



}
