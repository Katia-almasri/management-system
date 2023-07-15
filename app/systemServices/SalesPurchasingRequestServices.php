<?php
namespace App\systemServices;

use App\Models\outPut_Type_Production;
use App\Models\salesPurchasingRequset;
use App\Models\salesPurchasingRequsetDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SalesPurchasingRequest;
use Auth;
use Illuminate\Http\Request;

class SalesPurchasingRequestServices
{

    public function processSalesPurchasingRequestAmounts(SalesPurchasingRequest $request)
    {
        $totalAmount = $request->total_amount;

        $detailsAmounts = 0;
        foreach ($request->details as $_detail) {
            $detailsAmounts += $_detail['amount'];
        }

        if ($detailsAmounts != $totalAmount)
            return ["status" => false, "message" => "الكمية الكلية لا تساوي مجموع كميات تفاصيل الطلب"];
        return ["status" => true, "message" => "المجموع صحيح"];
    }

    public function calculcateTotalAmount(SalesPurchasingRequest $request)
    {
        $totalAmount = 0;
        foreach ($request->details as $_detail) {
            $totalAmount += $_detail['amount'];
        }
        return ["status" => true, "result" => $totalAmount];
    }

    public function calculateSaleInMonthGroupBy()
    {
        $currentMonth = date('m');

        $sales = DB::table('sales_purchasing_requests')
        ->join('sales-purchasing-requset-details', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
        ->where('sales_purchasing_requests.request_type', '=', 1)
        ->where('sales_purchasing_requests.accept_by_ceo', 1)
        ->where('sales_purchasing_requests.accept_by_sales', 1)
        ->where('sales_purchasing_requests.command', 1)
        ->select('type', DB::raw('SUM(amount) as monthly_amount'))
        ->whereMonth('sales-purchasing-requset-details.created_at', '=', 6)
        ->groupBy('type')
        ->get();
       
        return ["result" => $sales];
    }

    public function makeTheDataToInputToCSVFile($inputData)
    {
        $outputTypeProduction = outPut_Type_Production::get();
        $resultData = [];
        $resultData[] = date('Y-m');
        $cnt = 0;
        $flag = false;
        //loop over the array
        foreach ($outputTypeProduction as $_type) {
            foreach ($inputData as $_input) {
                $cnt += 1;
                if ($_type->type == $_input->type) {
                    $flag = true;
                    $resultData[] = $_input->monthly_amount;
                    break;
                }

            }
            if($flag == true){
                $flag = false;
                $cnt = 0;
            }
            if ($cnt == count($inputData)) {
                if ($flag == false) {
                    //the type did not sold this month
                    $resultData[] = 0.0;
                    $flag = false;
                    $cnt = 0;

                }
            }
        }
        return ["result" => $resultData];

    }


    public function appendToCSVFile($data)
    {
        $handle = fopen('public/storage/AI/sales-final2.csv', 'a');
        fputcsv($handle, $data);
        fclose($handle);
    }
}