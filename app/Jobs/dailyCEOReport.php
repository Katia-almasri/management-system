<?php

namespace App\Jobs;

use App\systemServices\ceoServices;
use App\systemServices\notificationServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailyCEOReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ceoService;
    protected $notificationService;

    public function __construct()
    {
        $this->ceoService = new ceoServices();
        $this->notificationService = new notificationServices();
    }

   
    public function handle()
    {
        try {
            $filename = 'daily_ceo_report_' . date('Y_m_d') . '.txt';
            DB::beginTransaction();
            $numberSales = $this->ceoService->dailyNumberOfSalesRequest();
            $acceptedNumberSales = $this->ceoService->dailySalesRequestِApproved();
            $numberOfPurchas = $this->ceoService->dailyNumberOfPurchasRequest();
            $acceptedNumberOfPurchas = $this->ceoService->dailyPurchasRequestApproved();
            $PurchasePriceforThisDay = $this->ceoService->dailyPurchasePriceforThisDay();
            $SalesPriceforThisDay = $this->ceoService->dailySalesPriceforThisDay();


            $report = [
                $numberSales,
                $acceptedNumberSales,
                $numberOfPurchas,
                $acceptedNumberOfPurchas,
                $PurchasePriceforThisDay,
                $SalesPriceforThisDay,
            ];

            Storage::put($filename, json_encode($report));
            // $data = $this->notificationService->makeNotification(
            //     'daily-ceo-report-ready',
            //     'App\\Events\\dailyCEOReportReady',
            //     'التقرير اليومي للمدير التنفيذي',
            //     '',
            //     0,
            //     '',
            //     0,
            //     '',
            //     ''
            // );

            // $this->notificationService->generateDailyCEOReport($data);
            DB::commit();
            
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }
    }
}
