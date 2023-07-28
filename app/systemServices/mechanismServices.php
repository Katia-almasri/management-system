<?php
namespace App\systemServices;

use App\Models\Notification;
use App\Models\Trip;
use Illuminate\Support\Facades\DB;
use App\Exceptions\Exception;
use Auth;
use Illuminate\Http\Request;
use Pusher\Pusher;
use Carbon\Carbon;

class mechanismServices
{
    public function dailyNumTrips()
    {
        $dailyTrips = Trip::with('driver', 'truck', 'requset1')->whereDate('created_at', Carbon::today()->format('Y-m-d'))
        ->orderBy('created_at', 'DESC')    
        ->get();
            return(["dailyTrips"=>$dailyTrips]);
    }


    public function dailyTotalweightFromFarms(){
        $totalWeightFromFarms = Trip::select(DB::raw('DATE_FORMAT(trips.created_at,"%YYYY %M %D") as date'), DB::raw('sum(sales_purchasing_requests.total_amount) as tot'))
        ->join('sales_purchasing_requests', 'sales_purchasing_requests.id', '=', 'trips.sales_purchasing_requsets_id')
        ->where('trips.farm_id', '!=', null)
        ->whereDate('trips.created_at', Carbon::today()->format('Y-m-d'))
        ->where('status', 'تم الاستلام')
        ->groupBy('date')
        ->get();
        return(["totalWeightFromFarms"=>$totalWeightFromFarms]);

    }

    public function dailyTotalweightFromSellingPort(){
        $totalWeightFromSellingPort = Trip::select(DB::raw('DATE_FORMAT(trips.created_at,"%YYYY %M %D") as date'), DB::raw('sum(sales_purchasing_requests.total_amount) as tot'))
        ->join('sales_purchasing_requests', 'sales_purchasing_requests.id', '=', 'trips.sales_purchasing_requsets_id')
        ->where('trips.selling_port_id', '!=', null)
        ->whereDate('trips.created_at', Carbon::today()->format('Y-m-d'))
        ->where('status', 'تم الاستلام')
        ->groupBy('date')
        ->get();
        return(["totalWeightFromSellingPort"=>$totalWeightFromSellingPort]);

    }



    
}