<?php

namespace App\Jobs;

use App\systemServices\notificationServices;
use App\systemServices\SalesPurchasingRequestServices;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class dailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationService;
    protected $SalesPurchasingRequestServices;
    public function __construct()
    {
        $this->SalesPurchasingRequestServices = new SalesPurchasingRequestServices();
        $this->notificationService = new notificationServices();
    }

    public function handle()
    {
        try {
            $filename = 'daily_sales_report_' . date('Y_m_d') . '.txt';
            DB::beginTransaction();
            $salesToday = $this->SalesPurchasingRequestServices->salesToday();
            $PurchaseToday = $this->SalesPurchasingRequestServices->PurchaseToday();
            $totalPriceFromFarms = $this->SalesPurchasingRequestServices->totPriceFromFarms();

            $report = [
                $salesToday,
                $PurchaseToday,
                $totalPriceFromFarms,
            ];
            Storage::put($filename, json_encode($report));
            $data = $this->notificationService->makeNotification(
                'daily-sales-report-ready',
                'App\\Events\\dailySalesReportReady',
                'التقرير اليومي لمدير المشتريات والمبيعات ',
                '',
                0,
                '',
                0,
                '',
                ''
            );

            $this->notificationService->generateDailySalesReport($data);

            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }
    }
}
