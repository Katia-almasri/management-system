<?php

namespace App\Jobs;

use App\systemServices\mechanismServices;
use App\systemServices\notificationServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailyMechanismReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mechanismService;
    protected $notificationService;
    public function __construct()
    {
        $this->mechanismService = new mechanismServices();
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
            $filename = 'daily_mechanism_report_' . date('Y_m_d') . '.txt';
            DB::beginTransaction();
            $dailyTrips = $this->mechanismService->dailyNumTrips();
            $totalWeightFromFarms = $this->mechanismService->dailyTotalweightFromFarms();
            $totalWeightFromSellingPort = $this->mechanismService->dailyTotalweightFromSellingPort();

            $report = [
                $dailyTrips,
                $totalWeightFromFarms,
                $totalWeightFromSellingPort 
            ];

            Storage::put($filename, json_encode($report));
            $data = $this->notificationService->makeNotification(
                'daily-mechanism-report-ready',
                'App\\Events\\dailyMechanismReportReady',
                'التقرير اليومي لمنسق حركة الآليات',
                '',
                0,
                '',
                0,
                '',
                ''
            );

            $this->notificationService->generateDailyMechanismReport($data);
            DB::commit();

        }catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }
    }
}
