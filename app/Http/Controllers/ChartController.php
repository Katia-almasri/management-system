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
use App\Models\Truck;
use App\Models\PoultryReceiptDetection;

use App\Models\Driver;
use App\Models\Trip;


class ChartController extends Controller
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


    //منسق حركة الاليات
    public function CountAvaiableTrucks(Request $request){
        $TruckAvaiable = Truck::where('state','متاحة')->get();
        return response()->json($TruckAvaiable->count());
    }

    public function CountTrucks(Request $request){
        $Trucks = Truck::get();
        return response()->json($Trucks->count());
    }

    public function CountDriver(Request $request){
        $Drivers = Driver::get();
        return response()->json($Drivers->count());
    }

    public function CountAvaiableDriver(Request $request){
        $DriverAvaiable = Driver::where('state','متاح')->get();
        return response()->json($DriverAvaiable->count());
    }

    public function CountTrip()
    {
        $Trips = Trip::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('id','ASC')
                    ->pluck('count', 'month_name');
        $labels = $Trips->keys();
        $data = $Trips->values();
        return response()->json([

            'labels' => $labels,
            'data' => $data,
    ]);
        return response()->json($Trips);
    }


    ////////////امر القبان

    ///////عدد الكشوف الكلي هذا الشهر
    public function CountPoultryReceiptDetection(Request $request){
        $PoultryReceiptDetection = PoultryReceiptDetection:: whereMonth('created_at', Carbon::now()->month)->get();
        return response()->json($PoultryReceiptDetection->count());
    }

    ////////عدد الكشوف التي لم تزان بعد
    public function CountPoultryReceiptDetectionwhereNotAfter(Request $request){
        //عدد الكشوف يلي نزانت
        $PoultryReceiptDetectionwhere = PoultryReceiptDetection::doesnthave('weightAfterArrivalDetection')-> whereMonth('created_at', Carbon::now()->month)->get();
        return response()->json($PoultryReceiptDetectionwhere->count());
    }

    /////////////عدد الرحلات التي في الرحلة
    public function CountTripInRoad(Request $request){
        $tripInRoad = Trip::where([['status','في الرحلة'],['farm_id','!=',null]])
        ->whereMonth('created_at', Carbon::now()->month)->get();
        return response()->json($tripInRoad->count());
    }


    public function ChartPoultryReceiptDetection(Request $request){
        $PoultryReceiptDetection = PoultryReceiptDetection::select(DB::raw("COUNT(*) as count"), DB::raw("MONTHNAME(created_at) as month_name"))
                    ->whereYear('created_at', date('Y'))
                    ->groupBy(DB::raw("month_name"))
                    ->orderBy('id','asc')
                    ->pluck('count', 'month_name');

                    $labels = $PoultryReceiptDetection->keys();
                    $data = $PoultryReceiptDetection->values();
                    return response()->json([

                        'labels' => $labels,
                        'data' => $data,
                ]);

        return response()->json($PoultryReceiptDetection);
    }



}
