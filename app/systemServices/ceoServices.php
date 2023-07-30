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
            ->whereMonth('created_at', date('m'))
            ->where([['request_type', 1]])
            ->where([['accept_by_sales', 1], ['accept_by_ceo', null]])
            ->orderby('id', 'desc')->get();
        $totalPrice = 0;
        if (count($sales) > 0) {
            foreach ($sales as $_detail) {
                $details = $_detail['salesPurchasingRequsetDetail'];
                foreach ($details as $_detailRequest) {
                    $totalPrice += $_detailRequest['price'];
                }
                $_detail['totalPrice'] = $totalPrice;
                $totalPrice = 0;
            }
            return (["sales" => $sales]);


        }
    }

    //  طلبات البيع المؤكدة من قبل المدير التنفيذي
    public function dailySalesRequestِApproved()
    {
        $acceptedSales = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->whereMonth('created_at', date('m'))
            ->where([['request_type', 1], ['accept_by_ceo', 1]])->get();
        $totalPrice = 0;
        if (count($acceptedSales) > 0) {
            foreach ($acceptedSales as $_detail) {
                $details = $_detail['salesPurchasingRequsetDetail'];
                foreach ($details as $_detailRequest) {
                    $totalPrice += $_detailRequest['price'];
                }
                $_detail['totalPrice'] = $totalPrice;
                $totalPrice = 0;

            }
            return (["acceptedSales" => $acceptedSales]);
        }
    }


    //  طلبات الشراء
    public function dailyNumberOfPurchasRequest()
    {
            $Purchas = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
                ->whereMonth('created_at', date('m'))
                ->where([['request_type', 0]])
                ->where([['accept_by_sales', 1], ['accept_by_ceo', null]])->orderby('id', 'desc')->get();

            $totalPrice = 0;
            if (count($Purchas) > 0) {
                foreach ($Purchas as $_detail) {
                    $details = $_detail['salesPurchasingRequsetDetail'];
                    foreach ($details as $_detailRequest) {
                        $totalPrice += $_detailRequest['price'];
                    }
                    $_detail['totalPrice'] = $totalPrice;
                    $totalPrice = 0;

                }
            return (["Purchas" => $Purchas]);
        }
    }

    //  طلبات الشراء المؤكدة من المدير التنفيذي
    public function dailyPurchasRequestApproved()
    {
        $acceptedPurchas = salesPurchasingRequset::with('farm', 'sellingPort', 'salesPurchasingRequsetDetail')
            ->whereMonth('created_at', date('m'))
            ->where([['request_type', 0]])
            ->where([['accept_by_sales', 1], ['accept_by_ceo', 1]])->orderby('id', 'desc')->get();
        $totalPrice = 0;
        if (count($acceptedPurchas) > 0) {
            foreach ($acceptedPurchas as $_detail) {
                $details = $_detail['salesPurchasingRequsetDetail'];
                foreach ($details as $_detailRequest) {
                    $totalPrice += $_detailRequest['price'];
                }
                $_detail['totalPrice'] = $totalPrice;
                $totalPrice = 0;

            }
            return (["acceptedPurchas" => $acceptedPurchas]);
        }
    }

    // مبالغ المبيع اليوم
    public function dailyPurchasePriceforThisDay()
    {
        $PurchasePriceforThisDay = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
            ->join('sales-purchasing-requset-details', 'sales-purchasing-requset-details.requset_id', '=', 'sales_purchasing_requests.id')
            ->whereMonth('sales_purchasing_requests.created_at', date('m'))
            ->where([['request_type', 0], ['accept_by_sales', 1], ['accept_by_ceo', 1]])
            ->get('sum');
        return (["PurchasePriceforThisDay" => $PurchasePriceforThisDay]);
    }

    // مبالغ الشراء اليوم
    public function dailySalesPriceforThisDay()
    {
        $SalesPriceforThisDay = salesPurchasingRequset::select(DB::raw("SUM(price) as sum"))
            ->join('sales-purchasing-requset-details', 'sales-purchasing-requset-details.requset_id', '=', 'sales_purchasing_requests.id')
            ->whereMonth('sales_purchasing_requests.created_at', date('m'))
            ->where([['request_type', 1], ['accept_by_sales', 1], ['accept_by_ceo', 1]])
            ->get('sum');
        return (["SalesPriceforThisDay" => $SalesPriceforThisDay]);
    }



}