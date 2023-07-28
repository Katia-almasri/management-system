<?php
namespace App\systemServices;

use App\Models\salesPurchasingRequset;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ceoServices
{
    //  طلبات منتفذ البيع اليوم
    public function dailyNumberOfSalesRequest()
    {
        $sales = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->whereDate('created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 1]])
            ->where([['accept_by_sales', 1], ['accept_by_ceo', null]])
            ->orderby('id', 'desc')->get();
        return (["sales" => $sales]);
    }

    //  طلبات البيع المؤكدة من قبل المدير التنفيذي
    public function dailySalesRequestِApproved()
    {
        $acceptedSales = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
        ->whereDate('created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 1], ['accept_by_ceo', 1]])->get();
        return (["acceptedSales" => $acceptedSales]);
    }


    //  طلبات الشراء
    public function dailyNumberOfPurchasRequest()
    {
        $Purchas = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->whereDate('created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 0]])
            ->where([['accept_by_sales', 1], ['accept_by_ceo', null]])->orderby('id', 'desc')->get();
        return (["Purchas" => $Purchas]);
    }

    //  طلبات الشراء المؤكدة من المدير التنفيذي
    public function dailyPurchasRequestApproved()
    {
        $acceptedPurchas = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->whereDate('created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 0]])
            ->where([['accept_by_sales', 1], ['accept_by_ceo', 1]])->orderby('id', 'desc')->get();
        return (["acceptedPurchas" => $acceptedPurchas]);
    }

    // مبالغ المبيع اليوم
    public function dailyPurchasePriceforThisDay()
    {
        $PurchasePriceforThisDay = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
            ->join('sales-purchasing-requset-details', 'sales-purchasing-requset-details.requset_id', '=', 'sales_purchasing_requests.id')
            ->whereDate('sales_purchasing_requests.created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 0], ['accept_by_sales', 1], ['accept_by_ceo', 1]])
            ->pluck('sum');
        return (["PurchasePriceforThisDay" => $PurchasePriceforThisDay]);
    }

    // مبالغ الشراء اليوم
    public function dailySalesPriceforThisDay()
    {
        $SalesPriceforThisDay = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
            ->join('sales-purchasing-requset-details', 'sales-purchasing-requset-details.requset_id', '=', 'sales_purchasing_requests.id')
            ->whereDate('sales_purchasing_requests.created_at', Carbon::today()->format('Y-m-d'))
            ->where([['request_type', 1], ['accept_by_sales', 1], ['accept_by_ceo', 1]])
            ->pluck('sum');
        return (["SalesPriceforThisDay" => $SalesPriceforThisDay]);
    }



}