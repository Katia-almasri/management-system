<?php

namespace App\Jobs;

use App\systemServices\notificationServices;
use App\systemServices\poultryDetectionRequestServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailyLibraRepoer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationService;
    protected $poultryDetectionRequestServices;
    public function __construct()
    {
        $this->poultryDetectionRequestServices = new poultryDetectionRequestServices();
        $this->notificationService = new notificationServices();

    }

  
    public function handle()
    {
        try {
            $filename = 'daily_libra_report_' . date('Y_m_d') . '.txt';
            DB::beginTransaction();
            $notWeighterReciepts = $this->poultryDetectionRequestServices->dailyNotWeightedReciepts();
            $weighterReciepts = $this->poultryDetectionRequestServices->dailyWeightedReciepts();
            $dailyStatis = $this->poultryDetectionRequestServices->dailyStatistisReciepts();

            $report = [
                $notWeighterReciepts,
                $weighterReciepts,
                $dailyStatis,
            ];

            Storage::put($filename, json_encode($report));
            $data = $this->notificationService->makeNotification(
                'daily-libra-report-ready',
                'App\\Events\\dailyLibraReportReady',
                'التقرير اليومي لآمر القبان',
                '',
                0,
                '',
                0,
                '',
                ''
            );

            $this->notificationService->generateDailyLibraReport($data);

            DB::commit();
        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }      
    }
}
