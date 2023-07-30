<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesPurchasingRequest;
use App\Models\Command;
use App\Models\input_slaughter_table;
use App\Models\OutputManufacturing;
use App\Models\output_cutting;
use App\Models\outPut_SlaughterSupervisor_table;
use App\Models\outPut_Type_Production;
use App\Models\PoultryReceiptDetection;
use App\Models\Prediction;
use App\Models\salesPurchasingRequset;
use App\Models\Trip;
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
        if (count($predictions) == 0) {
            return response()->json(['status' => false, 'message' => "لم يتم توقع المبيعات للشهر القادم بعد"]);
        }
        foreach ($predictions as $_prediction) {
            $formattedDate = Carbon::parse($_prediction->year_month)->format('Y-m');
            $_prediction->year_month = $formattedDate;
        }

        $data = [];
        $data['predictions'] = $predictions;
        $data['year_month'] = $predictions[0]->year_month;
        return response()->json(["status" => true, "message" => $data]);




    }

    public function d(Request $request)
    {

        $salesToday = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')->where([['request_type', 0], ['accept_by_ceo', 1], ['command', 1]])
        ->whereHas('trips')
        ->whereMonth('created_at', date('m'))
        ->get();
            return response()->json($salesToday);

        
    }
}