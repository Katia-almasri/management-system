<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use Auth;
use DB;

use App\Models\Manager;
use App\Models\Farm;
use App\Models\SellingPort;
use App\Models\salesPurchasingRequset;


class ChartSalesController extends Controller
{
    use validationTrait;


    // public function CountManager()
    // {
    //     $users = Manager::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
    //                 ->whereYear('created_at', date('Y'))
    //                 ->groupBy(DB::raw("month_name"))
    //                 ->orderBy('id','ASC')
    //                 ->pluck('count', 'month_name');

    //     $labels = $users->keys();
    //     $data = $users->values();

    //     return response()->json($users);
    // }


    //مدير المشتريات والمبيعات
    ///////////////////////عدد المزارع/////////////////////
    public function CountFarm(Request $request){
        $Farms = Farm::get();
        return response()->json($Farms->count());
    }

    ////////////////////عدد منافذ البيع/////////////////////
    public function CountSellingPort(Request $request){
        $SellingPort = SellingPort::get();
        return response()->json($SellingPort->count());
    }

    ///////////////////////عدد عمليات الشراء///////////////////////////
    // public function CountPurchase(Request $request){
    //     $Purchases = SalesPurchasingRequestController::where([['request_type',0],['accept_by_sales',1],['accept_by_ceo',1]])->get();
    //     return response()->json($Purchases->count());
    // }
    // ///////////////////////عدد عمليات المبيع///////////////////////////
    // public function CountSales(Request $request){
    //     $sales = salesPurchasingRequset::where([['request_type',1],['accept_by_sales',1],['accept_by_ceo',1]])->get();
    //     return response()->json($sales->count());
    // }
    public function ChartPurchase(Request $request)
    {
        $CountPurchase = salesPurchasingRequset::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->where([['request_type',0],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('id','ASC')
                    ->pluck('count', 'month_name');
        $labels = $CountPurchase->keys();
        $data = $CountPurchase->values();
        return response()->json([

            'labels' => $labels,
            'data' => $data,
    ]);
    }

    public function ChartSales(Request $request)
    {
        $CountSales = salesPurchasingRequset::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->where([['request_type',1],['accept_by_sales',1],['accept_by_ceo',1]])
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('id','ASC')
                    ->pluck('count', 'month_name');
        $labels = $CountSales->keys();
        $data = $CountSales->values();
        return response()->json([

            'labels' => $labels,
            'data' => $data,
    ]);

    }

    public function sortByTheBestFarm(Request $request){
        $purchaseRequest =
        salesPurchasingRequset::with(['farm' => function ($query) {
            $query->select('id','name');
        }])->groupBy('farm_id')
        ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw( ' farm_id,count(*) as total')
        ->where('farm_id','!=',null)
        ->orderBy('total','desc')
        ->get();
        return response()->json($purchaseRequest);
    }

    public function sortByTheBestSellingPort(Request $request){
        $salesRequest =
        salesPurchasingRequset::with(['sellingPort' => function ($query) {
            $query->select('id','name');
        }])->groupBy('selling_port_id')
        ->whereMonth('created_at', Carbon::now()->month)
        ->selectRaw( 'selling_port_id,count(*) as total')
        ->where('selling_port_id','!=',null)
        ->orderBy('total','desc')
        ->get();
        return response()->json($salesRequest);
    }

}
