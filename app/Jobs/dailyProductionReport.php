<?php

namespace App\Jobs;

use App\systemServices\notificationServices;
use App\systemServices\productionServices;
use App\systemServices\warehouseServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailyProductionReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productionService;
    protected $notificationService;
    protected $warehouseService;
    public function __construct()
    {
        $this->productionService = new productionServices();
        $this->notificationService = new notificationServices();
        $this->warehouseService = new warehouseServices();

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $filename = 'daily_production_report_' . date('Y_m_d') . '.txt';
            DB::beginTransaction();
            $inputProduction = $this->productionService->dailyInputProduction();
            $outputSlaughter = $this->productionService->dailyOutputSlaughter();
            $totWeightOutputSlaughter = $this->productionService->dailytTotalWeightFromSlaughter();
            $outputCutting = $this->productionService->dailyOutputCutting();
            $totWeightOutputCutting = $this->productionService->dailytTotalWeightFromCutting();
            $outputManufacturing = $this->productionService->dailyOutputManufacturing();
            $totWeightOutputManufactoring = $this->productionService->dailytTotalWeightFromManufactoring();
            $doneCommands = $this->warehouseService->getDoneCommands();
            $nonDoneCommands = $this->warehouseService->getNotDoneCommands();

            $report = [
                $inputProduction,
                $outputSlaughter,
                $totWeightOutputSlaughter,
                $outputCutting,
                $totWeightOutputCutting,
                $outputManufacturing,
                $totWeightOutputManufactoring,
                $doneCommands,
                $nonDoneCommands,
            ];

            Storage::put($filename, json_encode($report));
            $data = $this->notificationService->makeNotification(
                'daily-production-report-ready',
                'App\\Events\\dailyProductionReportReady',
                'التقرير اليومي لمدير الإنتاج ',
                '',
                0,
                '',
                0,
                '',
                ''
            );

            $this->notificationService->generateDailyProductionReport($data);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }
    }
}