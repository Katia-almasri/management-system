<?php

namespace App\Jobs;

use App\Models\Prediction;
use App\systemServices\notificationServices;
use App\systemServices\SalesPurchasingRequestServices;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class predictions implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $notificationService;
    protected $salesService;

    public function __construct()
    {
        $this->notificationService = new notificationServices();
        $this->salesService = new SalesPurchasingRequestServices();
    }


    public function handle()
    {
        try {
            DB::beginTransaction();
            //1. make a report for sales for each output_production monthly
            $result1 = $this->salesService->calculateSaleInMonthGroupBy();
            $result2 = $this->salesService->makeTheDataToInputToCSVFile($result1['result']);
            $this->salesService->appendToCSVFile($result2['result']);


            //2. add to the csv file(sales.csv);
            $output = exec('python ' . "public/storage/AI/Prediction_Model.py");
            $result = json_decode($output);

            $date = Carbon::now();
            $nextMonth = $date->addMonth()->startOfMonth();

            //3. store in Database
            foreach ($result as $_r) {
                $prediction = new Prediction();
                $prediction->year_month = $nextMonth->toDateTimeString();
                $prediction->expected_weight = $_r->value;
                $prediction->output_type = $_r->name;
                $prediction->save();

            }
            //4. send notification

            $data = $this->notificationService->makeNotification(
                'predictions',
                'App\\Events\\predictionsNotification',
                'تم صدور توقعات مبيعات الشهر القادم',
                '',
                0,
                '',
                0.0,
                '',
                ''

            );

            $this->notificationService->predictionsNotification($data);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }

    }
}