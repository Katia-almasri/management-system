<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Models\AddStockpileNotif;
use App\Models\Command;
use App\Exceptions\Exception;
use App\Models\CommandDetail;
use App\Models\Command_sales;
use App\Models\DetonatorFrige1;
use App\Models\DetonatorFrige1Detail;
use App\Models\DetonatorFrige1Output;
use App\Models\DetonatorFrige2;
use App\Models\DetonatorFrige2Detail;
use App\Models\DetonatorFrige2Output;
use App\Models\DetonatorFrige3;
use App\Models\DetonatorFrige3Detail;
use App\Models\DetonatorFrige3Output;
use App\Models\Exipration;
use App\Models\Lake;
use App\Models\LakeDetail;
use App\Models\LakeOutput;
use App\Models\Notification;
use App\Models\Remnat;
use App\Models\Store;
use App\Models\StoreDetail;
use App\Models\StoreOutput;
use App\Models\Warehouse;
use App\Models\WarehouseType;
use App\Models\ZeroFrige;
use App\Models\ZeroFrigeDetail;
use App\Models\ZeroFrigeOutput;
use App\systemServices\notificationServices;
use App\systemServices\warehouseServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Validator;

class WarehouseController extends Controller
{

    protected $warehouseService;
    protected $notificationService;

    public function __construct()
    {
        $this->warehouseService = new warehouseServices();
        $this->notificationService = new notificationServices();
    }

    //////////////////////// LAKES //////////////////////////////
    public function inputFromLakeToOutput(Request $request)
    {
        $warning_output_names = array();
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->warehouseService->outputWeightFromLake($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);
                if ($result['notification'] != null) {
                    array_push($warning_output_names, $result['notification']);
                }
            }
            DB::commit();

            return response()->json(["status" => true, "message" => "تمت عملية الإخراج بنجاح", "warning_output_names" => $warning_output_names]);

        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }

    public function displayLakeContent(Request $request)
    {
        $lakes = Lake::where([['weight', '!=', 0]])->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($lakes);
    }

    //////////////////// ZERO FRIGE //////////////////////
    public function displayZeroFrigeContent(Request $request)
    {
        $zeroFriges = ZeroFrige::where('weight', '!=', 0)->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($zeroFriges);

    }

    public function inputFromZeroToOutput(Request $request)
    {
        $warning_output_names = array();
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->warehouseService->outputWeightFromZero($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);
                if ($result['notification'] != null) {
                    array_push($warning_output_names, $result['notification']);
                }

            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تمت عملية الإخراج بنجاح', "warning_output_names" => $warning_output_names]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }

    //////////////////// DETONATOR 1 ////////////////////////

    public function inputFromDet1ToOutput(Request $request)
    {
        $warning_output_names = array();
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->warehouseService->outputWeightFromDet1($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);
                if ($result['notification'] != null) {
                    array_push($warning_output_names, $result['notification']);
                }
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح', "warning_output_names" => $warning_output_names]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    public function displayDetonatorFrige1Content()
    {
        $detonatorFrige1 = DetonatorFrige1::where('weight', '!=', 0)->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($detonatorFrige1);

    }

    ///////////////////// DETONATOR 2 ////////////////////

    public function displayDetonatorFrige2Content()
    {
        $detonatorFrige2 = DetonatorFrige2::where('weight', '!=', 0)->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($detonatorFrige2);

    }

    public function inputFromDet2ToOutput(Request $request)
    {
        $warning_output_names = array();
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->warehouseService->outputWeightFromDet2($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);
                if ($result['notification'] != null) {
                    array_push($warning_output_names, $result['notification']);
                }
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح', "warning_output_names" => $warning_output_names]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    ////////////////////////// DETONATOR 3 ///////////////////////
    public function displayDetonatorFrige3Content()
    {
        $detonatorFrige3 = DetonatorFrige3::where('weight', '!=', 0)->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($detonatorFrige3);

    }

    public function inputFromDet3ToOutput(Request $request)
    {
        $warning_output_names = array();
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            DB::beginTransaction();
            foreach ($request->details as $_detail) {
                $result = $this->warehouseService->outputWeightFromDet3($_detail, $request['outputChoice']);
                if ($result['status'] == false)
                    throw new \ErrorException($result['message']);
                if ($result['notification'] != null) {
                    array_push($warning_output_names, $result['notification']);
                }
            }
            DB::commit();
            return response()->json(["status" => true, "message" => 'تم الإدخال بنجاح', "warning_output_names" => $warning_output_names]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    /////////////////// STORE CONTENT /////////////////////////
    public function displayStoreContent()
    {
        $store = Store::where('weight', '!=', 0)->with('warehouse.outPut_Type_Production')
            ->get();
        return response()->json($store);

    }
    //////////////////// remnant content ///////////////////////

    public function displayRemnantContent()
    {
        $store = Remnat::where('weight', '!=', 0)->with('type_remnat')
            ->get();
        return response()->json($store);

    }



    /////////////// WAREHOUSE FEATURES ///////////////

    public function displayWarehouseDetail(Request $request, $warehouseId)
    {
        $warehouseDetail = Warehouse::where('id', $warehouseId)
            ->with(['zeroFrige', 'lake', 'detonatorFrige1', 'detonatorFrige2', 'detonatorFrige3', 'store'])->get();
        return response()->json($warehouseDetail);
    }
    public function editWarehouseRowInfo(Request $request, $warehouseId)
    {

        $validator = Validator::make(
            $request->all(),
            [
                "minimum" => "numeric|gt:0",
                "stockpile" => "numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $warehouseRow = Warehouse::find($warehouseId);
        $warehouseRow->update(['minimum' => $request['minimum'], 'stockpile' => $request['stockpile']]);
        return response()->json(["status" => false, "message" => 'تم التعديل بنجاح']);

    }

    //////////////////// FILL COMMAND FROM PRODUCTION MAANAGER //////////////////
    public function fillCommandFromProductionManager(Request $request, $commandId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $from = $request['from'];
        try {
            DB::beginTransaction();
            foreach ($request['details'] as $_detail) {
                $result = $this->warehouseService->fillCommand($_detail, $from);
                if ($result['status'] != true)
                    throw new \ErrorException($result['message']);
            }
            DB::commit();
            $message = 'تمت العملية بنجاح';
            $doneCommand = $this->warehouseService->checkIsCommandDone($commandId);
            if ($doneCommand['status'] == true)
                $message = $message . ' و' . $doneCommand['message'];
            return response()->json(["status" => true, "message" => $message]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }

    public function displayCommand(Request $request, $commandId)
    {
        $command = Command::whereHas('commandDetails', function ($q) {
            $q->where('is_filled', '=', 0);
        })
            ->with([
                'commandDetails' => function ($q) {
                    $q->with('warehouse.outPut_Type_Production')->where('is_filled', '=', 0);
                }
            ])->find($commandId);
        return response()->json($command);
    }

    public function displayWarehouseContentWithDetails(Request $request)
    {
        $warehouse = Warehouse::where([['tot_weight', '!=', 0], ['tot_weight', '!=', null]])->with(['outPut_Type_Production', 'zeroFrige', 'lake', 'detonatorFrige1', 'detonatorFrige2', 'detonatorFrige3', 'store'])->get();
        return response()->json($warehouse);
    }
    /////////////////////// COMMAND FROM SALES MANAGER ///////////
    public function fillCommandFromSalesManager(Request $request, $commandId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "details.*.weight" => "required|numeric|gt:0"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        $from = $request['from'];
        try {
            DB::beginTransaction();
            foreach ($request['details'] as $_detail) {
                $result = $this->warehouseService->fillCommandFromSalesManager($_detail, $from);
                if ($result['status'] != true)
                    throw new \ErrorException($result['message']);
            }
            DB::commit();
            $message = 'تمت العملية بنجاح';
            $doneCommand = $this->warehouseService->checkIsCommandSalesDone($commandId);
            if ($doneCommand['status'] == true) {
                $message = $message . ' و' . $doneCommand['message'];
                //send notification to mechanism coordinator
                //1. get the request tot aomount 
                $commandRequest = Command_sales::with('sales_request')->find($commandId);
                $data = $this->notificationService->makeNotification(
                    'command-sales-done',
                    'App\\Events\\commandSalesDoneNotif',
                    'تم إخراج المواد من المخزن بنجاح',
                    '',
                    $request->user()->id,
                    '',
                    $commandRequest->sales_request->total_amount,
                    'مدير المشتريات والمبيعات',
                    ''
                );

                $this->notificationService->commandSalesDoneNotif($data);

                //send notification to sales manager
                $data = $this->notificationService->makeNotification(
                    'command-sales-done',
                    'App\\Events\\commandSalesDoneNotif',
                    'تم إخراج المواد من المخزن بنجاح',
                    '',
                    $request->user()->id,
                    '',
                    $commandRequest->sales_request->total_amount,
                    'منسق حركة الآليات',
                    ''
                );

                $this->notificationService->commandSalesDoneNotif($data);

            }


            return response()->json(["status" => true, "message" => $message]);
        } catch (\Exception $exception) {
            DB::rollback();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }

    }
    /////////////////////// LAKE MOVEMENT (I/O) //////////////////
    //I
    public function displayLakeInputMov(Request $request)
    {
        $lakeMovement = Lake::with([
            'warehouse.outPut_Type_Production',
            'lakeDetails.inputable' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }
        ])->get();
        return response()->json($lakeMovement);
    }
    //O
    public function displayLakeOutMov(Request $request)
    {
        $lakeMovement = LakeOutput::with(['lake.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($lakeMovement);
    }

    /////////////////////// ZERO MOVEMENT (I/O) //////////////////
    public function displayZeroInputMov(Request $request)
    {
        $zeroMovement = ZeroFrige::with([
            'warehouse.outPut_Type_Production',
            'zeroFrigeDetails.inputable' => function ($query) {
                $query->orderBy('created_at', 'DESC');
            }
        ])->get();

        return response()->json($zeroMovement);
    }

    public function displayZeroOutMov(Request $request)
    {
        $lakeMovement = ZeroFrigeOutput::with(['zeroFrige.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($lakeMovement);
    }

    /////////////////////// DET1 MOVEMENT (I/O) //////////////////
    public function displayDet1InputMov(Request $request)
    {
        $det1Movement = DetonatorFrige1::with(['warehouse.outPut_Type_Production', 'detonatorFrige1Details.inputable'])->get();
        return response()->json($det1Movement);
    }

    public function displayDet1OutMov(Request $request)
    {
        $det1Movement = DetonatorFrige1Output::with(['detonator1.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($det1Movement);
    }

    /////////////////////// DET2 MOVEMENT (I/O) //////////////////
    public function displayDet2InputMov(Request $request)
    {
        $det2Movement = DetonatorFrige2::with(['warehouse.outPut_Type_Production', 'detonatorFrige2Details.inputable'])->get();
        return response()->json($det2Movement);
    }

    public function displayDet2OutMov(Request $request)
    {
        $det2Movement = DetonatorFrige2Output::with(['detonator2.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($det2Movement);
    }

    /////////////////////// DET3 MOVEMENT (I/O) //////////////////
    public function displayDet3InputMov(Request $request)
    {
        $det3Movement = DetonatorFrige3::with(['warehouse.outPut_Type_Production', 'detonatorFrige3Details.inputable'])->get();
        return response()->json($det3Movement);
    }

    public function displayDet3OutMov(Request $request)
    {
        $det3Movement = DetonatorFrige3Output::with(['detonator3.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($det3Movement);
    }

    /////////////////////// STORE MOVEMENT (I/O) //////////////////
    public function displayStoreInputMov(Request $request)
    {
        $storeMovement = Store::with(['warehouse.outPut_Type_Production', 'storeDetails.inputable'])->get();
        return response()->json($storeMovement);
    }

    public function displayStoreOutputMov(Request $request)
    {
        $storeMovement = StoreOutput::with(['store.warehouse.outPut_Type_Production', 'outputable'])->orderBy('created_at', 'DESC')->get();
        return response()->json($storeMovement);
    }
    /////////////////// END MOVEMENTS /////////////////////////////////
    public function displayCommands(Request $request)
    {
        $commands = Command::with('commandDetails.warehouse.outPut_Type_Production')->orderBy('created_at', 'DESC')->get();
        return response()->json($commands);
    }

    public function displayWarehousesTypes(Request $request)
    {
        $wareouseTypes = WarehouseType::get();
        return response()->json($wareouseTypes);
    }

    ////////////////// DESTRUCTION PART /////////////////////
    public function destructFromLakeDetails(Request $request, $lake_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }
        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $lakeDetail = LakeDetail::with('lake.warehouse.outPut_Type_Production')->find($lake_detail_id);
            $lakeDetail->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\LakeDetail';
            $newNotification->act_id = $lake_detail_id;
            $output_production_name = $lakeDetail->lake->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $lakeDetail->cur_weight;
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->output_from = 'مستودع البحرات';
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\LakeDetail';
            $data['output_detail_id'] = $lake_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);

            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\LakeDetail', $lake_detail_id, $lakeDetail->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }
    }

    public function destructFromZeroFrigeDetails(Request $request, $zero_frige_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $zeroFrigeDetail = ZeroFrigeDetail::with('zeroFrige.warehouse.outPut_Type_Production')->find($zero_frige_detail_id);
            $zeroFrigeDetail->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\ZeroFrigeDetail';
            $newNotification->act_id = $zero_frige_detail_id;
            $output_production_name = $zeroFrigeDetail->zeroFrige->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $zeroFrigeDetail->cur_weight;
            $newNotification->output_from = 'مستودع البراد الصفري';
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\ZeroFrigeDetail';
            $data['output_detail_id'] = $zero_frige_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);

            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\ZeroFrigeDetail', $zero_frige_detail_id, $zeroFrigeDetail->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);



        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }

    }

    public function destructFromDet1FrigeDetails(Request $request, $det1_frige_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $det1FrigeDetail = DetonatorFrige1Detail::with('detonatorFrige1.warehouse.outPut_Type_Production')->find($det1_frige_detail_id);
            $det1FrigeDetail->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\DetonatorFrige1Detail';
            $newNotification->act_id = $det1_frige_detail_id;
            $output_production_name = $det1FrigeDetail->detonatorFrige1->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $det1FrigeDetail->cur_weight;
            $newNotification->output_from = 'مستودع الصاعقة 1';
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\DetonatorFrige1Detail';
            $data['output_detail_id'] = $det1_frige_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);
            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige1Detail', $det1_frige_detail_id, $det1FrigeDetail->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }

    }

    public function destructFromDet2FrigeDetails(Request $request, $det2_frige_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $det2FrigeDetail = DetonatorFrige2Detail::with('detonatorFrige2.warehouse.outPut_Type_Production')->find($det2_frige_detail_id);
            $det2FrigeDetail->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\DetonatorFrige2Detail';
            $newNotification->act_id = $det2_frige_detail_id;
            $output_production_name = $det2FrigeDetail->detonatorFrige2->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $det2FrigeDetail->cur_weight;
            $newNotification->output_from = 'مستودع الصاعقة 2';
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\DetonatorFrige2Detail';
            $data['output_detail_id'] = $det2_frige_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);
            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige2Detail', $det2_frige_detail_id, $det2FrigeDetail->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }

    }

    public function destructFromDet3FrigeDetails(Request $request, $det3_frige_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $det3FrigeDetail = DetonatorFrige3Detail::with('detonatorFrige3.warehouse.outPut_Type_Production')->find($det3_frige_detail_id);
            $det3FrigeDetail->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\DetonatorFrige3Detail';
            $newNotification->act_id = $det3_frige_detail_id;
            $output_production_name = $det3FrigeDetail->detonatorFrige3->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $det3FrigeDetail->cur_weight;
            $newNotification->output_from = 'مستودع الصاعقة 3';
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\DetonatorFrige3Detail';
            $data['output_detail_id'] = $det3_frige_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);
            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\DetonatorFrige3Detail', $det3_frige_detail_id, $det3FrigeDetail->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }

    }

    public function destructFromStoreDetails(Request $request, $store_detail_id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "reason_of_notification" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        try {
            $outputTypeProduction = null;
            DB::beginTransaction();
            //1. update expiration date to now date
            $storeDetails = StoreDetail::with('store.warehouse.outPut_Type_Production')->find($store_detail_id);
            $storeDetails->update(['date_of_destruction' => Carbon::now()]);

            //2. send notification to expiration part
            $newNotification = new Notification();
            $newNotification->channel = 'add-output-to-expiration-warehouse-notification';
            $newNotification->event = 'App\\Events\\addOutputToExpirationWarehouseNotification';
            $newNotification->title = 'تم إخراج مادة إلى الإتلاف';
            $newNotification->route = 'App\Models\StoreDetail';
            $newNotification->act_id = $store_detail_id;
            $output_production_name = $storeDetails->store->warehouse->outPut_Type_Production->type;
            $newNotification->details = $output_production_name;
            $newNotification->is_seen = 0;
            $newNotification->weight = $storeDetails->cur_weight;
            $newNotification->output_from = 'المستودع النهائي';
            $newNotification->reason_of_notification = $request->reason_of_notification;
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'تم إخراج مادة إلى الإتلاف';
            $data['output_from'] = 'App\Models\StoreDetail';
            $data['output_detail_id'] = $store_detail_id;
            $data['details'] = ' تم إخراج المادة ' . $output_production_name . ' بنجاح ';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->addOutputExpiredNotif($data);
            //edit the weight in x_detail => in x => in warehouse
            $outputTypeProduction = $this->warehouseService->subtractFromWarehouseAfterExpiration('App\Models\StoreDetail', $store_detail_id, $storeDetails->cur_weight);

            DB::commit();
            return response()->json(["status" => true, "message" => 'تم إخراج المادة للإتلاف بنجاح', 'notification' => $outputTypeProduction]);


        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => 'حدث خطأ أثناء إخراج المادة للإتلاف']);
        }

    }

    public function displayAlloutputExpiration(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-output-to-expiration-warehouse-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'add-output-to-expiration-warehouse-notification'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);

    }

    public function getNotInputDestructedTypes(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-output-to-expiration-warehouse-notification'],
            ['expiration_id', '=', null]
        ])->orderBy('created_at', 'DESC')->get();
        return response()->json($notifications);
    }

    public function fillInputExpiration(Request $request, $notification_id)
    {
        try {
            DB::beginTransaction();
            //2. insesrt new row in expiration table
            $notification = Notification::find($notification_id);
            $difWeight = $notification->weight - $request->weight;
            if ($difWeight < 0)
                throw new \ErrorException('لا يمكن أن يكون وزن المادة المتلفة المستقبلة أكبر من المخرجة!');

            $expiration = new Exipration();
            $expiration->weight = $difWeight;
            $expiration->inputable_type = $notification->route;
            $expiration->inputable_id = $notification->act_id;
            $expiration->output_type_production = $notification->details;
            $expiration->output_from = $notification->output_from;
            $expiration->reason_of_expirations = $notification->reason_of_notification;
            $expiration->save();

            $notification->update(['expiration_id' => $expiration->id]);
            DB::commit();

            if ($difWeight == 0)
                return response()->json(["status" => true, "message" => 'تمت عملية إدخال المادة المتلفة إلى مخزن الإتلاف بنجاح']);
            else
                return response()->json(["status" => false, "message" => ' ' . $difWeight . 'تمت العملية بنجاح ولكن يوجد فاقد في المادة بمقدار ']);

        } catch (\Exception $exception) {
            DB::rollBack();
            return response()->json(["status" => false, "message" => $exception->getMessage()]);
        }
    }

    /////////////////// notification part ////////////////////

    public function displayExpirationNotification(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'add-output-to-expiration-warehouse-notification'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);
    }

    public function displayOutputExpiredDetail(Request $request, $notification_id)
    {
        $notification = Notification::where('channel', "add-output-to-expiration-warehouse-notification")->find($notification_id);
        return response()->json($notification);
    }

    public function displayExpirationWarehouse(Request $request)
    {
        $expirations = Exipration::with('inputable')->get();
        return response()->json($expirations);
    }



    // sales commands notification
    public function displaySalesCommandNotification(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'output-from-warehouse-to-sell'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);
    }

    public function displaySalesCommandNotificationSwitchState(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'output-from-warehouse-to-sell'],
            ['is_seen', '=', 0],
        ])->orderBy('created_at', 'DESC')->get();

        Notification::where([
            ['channel', '=', 'output-from-warehouse-to-sell'],
            ['is_seen', '=', 0],
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);
    }

    ///////////////////////// DAILY STATISTICS (in jobs) //////////////////////

    public function readDailyWarehouseReport(Request $request)
    {
        $filename = 'daily_warehouse_report_' . date('Y_m_d') . '.txt';
        if (Storage::exists($filename)) {

            $report = Storage::get($filename);
            $data = json_decode($report, true);
            return response()->json(["status" => true, "data" => $data]);

        }
        return response()->json(["status" => false, "data" => null, "message" => "لم يتم توليد التقرير لهذا اليوم بعد"]);
    }
    //display previous daily reports
    public function displayPreviousDailyReports(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "date" => "required"
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $newFormat = Carbon::parse($request->date)->format('Y_m_d');
        if ($newFormat > Carbon::now()->format('Y_m_d'))
            return response()->json(["status" => false, "message" => "لا يمكن توليد تقرير ليوم يلي اليوم الحالي!!"]);
        $filename = 'daily_warehouse_report_' . $newFormat . '.txt';
        if (Storage::exists($filename)) {
            $report = Storage::get($filename);
            $data = json_decode($report, true);
            return response()->json(["status" => true, "data" => $data]);

        }
        return response()->json(["status" => false, "data" => "لا يوجد تقرير لهذا اليوم بعد"]);
    }

    public function displayDailyWarehouseNotificationReports(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'daily-warehouse-report-ready'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();
        $notificationsCount = $notifications->count();
        return response()->json(['notifications' => $notifications, 'notificationsCount' => $notificationsCount]);

    }

    public function displayAllDailyWarehouseReportsNtification(Request $request)
    {
        $notifications = Notification::where([
            ['channel', '=', 'daily-warehouse-report-ready'],
            ['is_seen', '=', 0]
        ])->orderBy('created_at', 'DESC')->get();

        $updatedNotifications = Notification::where([
            ['channel', '=', 'daily-warehouse-report-ready'],
            ['is_seen', '=', 0]
        ])->update(['is_seen' => 1]);
        return response()->json($notifications);

    }



}