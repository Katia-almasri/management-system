<?php

namespace App\Jobs;

use App\Models\DetonatorFrige1Detail;
use App\Models\DetonatorFrige2Detail;
use App\Models\DetonatorFrige3Detail;
use App\Models\LakeDetail;
use App\Models\Notification;
use App\Models\StoreDetail;
use App\Models\ZeroFrigeDetail;
use App\systemServices\notificationServices;
use App\systemServices\warehouseServices;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailyWarehouseReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $warehouseService;
    protected $notificationService;

    public function __construct()
    {
        $this->warehouseService = new warehouseServices();
        $this->notificationService = new notificationServices();
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $filename = 'daily_warehouse_report_' . date('Y_m_d') . '.txt';
            
            $dailyInput = $this->warehouseService->dailyInputMovements();
            $dailyOutput = $this->warehouseService->dailyOutputMovements();
            $getExpirations = $this->warehouseService->getExpirations();
            $getDoneCommands = $this->warehouseService->getDoneCommands();
            $getNotDoneCommands = $this->warehouseService->getNotDoneCommands();
            $getWarehouseUnderStockpile = $this->warehouseService->getWarehouseUnderStockpile();
            $getOutputTypesInsertedToExpirationWarehouse = $this->warehouseService->getOutputTypesInsertedToExpirationWarehouse();
            $getNonInsertedExpiredToExpirationWarehouse = $this->warehouseService->getNonInsertedExpiredToExpirationWarehouse();
            $getMaterialsHavslistInDestructionWarehouse = $this->warehouseService->getMaterialsHavslistInDestructionWarehouse();
            
    
            $report = [
                "dailyInput"=>$dailyInput,
                "dailyOutput"=>$dailyOutput,
                "getExpirations"=>$getExpirations,
                "getDoneCommands"=>$getDoneCommands,
                "getNotDoneCommands"=>$getNotDoneCommands,
                "getWarehouseUnderStockpile"=>$getWarehouseUnderStockpile,
                "getOutputTypesInsertedToExpirationWarehouse"=>$getOutputTypesInsertedToExpirationWarehouse,
                "getNonInsertedExpiredToExpirationWarehouse"=>$getNonInsertedExpiredToExpirationWarehouse,
                "getMaterialsHavslistInDestructionWarehouse"=>$getMaterialsHavslistInDestructionWarehouse
                
            ];
            
            Storage::put($filename, json_encode($report));
            //send notificiation that the daily warehouse report has been ready
          
            $newNotification = new Notification();
            $newNotification->channel = 'daily-warehouse-report-ready';
            $newNotification->event = 'App\\Events\\dailyWarehouseReportReady';
            $newNotification->title = 'التقرير اليومي للمخازن';
            $newNotification->route = '';
            $newNotification->act_id = 0;
            $newNotification->details = '';
            $newNotification->is_seen = 0;
            $newNotification->weight = 0;
            $newNotification->output_from = '';
            $newNotification->reason_of_notification = '';
            $newNotification->save();

            //إرسال إشعار لمشرف المخازن
            $data['title'] = 'التقرير اليومي للمخازن';
            $data['details'] = 'تم توليد التقرير اليومي للمخازن';
            $data['date'] = date("Y-m-d", strtotime(Carbon::now()));
            $data['time'] = date("h:i A", strtotime(Carbon::now()));

            $this->notificationService->generateDailyWarehouseReport($data);

        } catch (\Exception $e) {
            Storage::put($filename, json_encode(["error"=> $e->getMessage()]));
        }

    }
}
