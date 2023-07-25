<?php

namespace App\Http\Controllers;

use App\Models\outPut_Type_Production;
use App\Models\Prediction;
use App\systemServices\SalesPurchasingRequestServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;



class calendarController extends Controller
{
    protected $salesService;

    public function __construct()
    {
        $this->salesService = new SalesPurchasingRequestServices();
    }

    public function getEvents(Request $request)
    {
        $api_key = 'f4f6baf0bafc9ca949cca66d53c8c85fadadb2aa';

        $country = 'SY';
        $year = 2023;
        $month = 4;

        $client = new Client();

        $response = $client->request('GET', "https://calendarific.com/api/v2/holidays?api_key={$api_key}&country={$country}&year={$year}&month={$month}");

        $holidays = json_decode($response->getBody(), true)['response']['holidays'];

        return response()->json($holidays);


    }

    public function getPredictions(Request $request)
    {
        $currentDate = Carbon::now();
        $nextMonth = $currentDate->addMonth()->startOfMonth();
        $predictions = Prediction::select('year_month', 'expected_weight', 'output_type')
        ->where('year_month', '<=', $nextMonth->toDateTimeString())->get();
        if(count($predictions)==0){
            return response()->json(['status'=>false, 'message'=>"لم يتم توقع المبيعات للشهر القادم بعد"]);
        }
        foreach ($predictions as $_prediction) {
            $formattedDate = Carbon::parse($_prediction->year_month)->format('Y-m');
            $_prediction->year_month = $formattedDate;
        }
        $data = [];
        $data['predictions'] = $predictions;
        $data['year_month'] = $predictions[0]->year_month;
        return response()->json(['status'=>true, 'message'=>$predictions]);
        


    }

    public function d(Request $request)
    {
        try {
            DB::beginTransaction();
            $result1 = $this->salesService->calculateSaleInMonthGroupBy();
            $result2 = $this->salesService->makeTheDataToInputToCSVFile($result1['result']);
            $this->salesService->appendToCSVFile($result2['result']);

            DB::commit();
            return response()->json(["status" => true, "msg" => "good"]);

        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json(["status" => false, "msg" => $ex->getMessage()]);
        }
    }
}