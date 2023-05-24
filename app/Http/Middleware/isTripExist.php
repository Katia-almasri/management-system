<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\salesPurchasingRequset;
use App\Models\Driver;
use App\Models\Truck;
use App\Models\Trip;


class isTripExist
{

    use validationTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $requestId = $request->requestId;
        $requestExist = salesPurchasingRequset::find($requestId);
        $driverExist = Driver::find($request->driver_id);
        $truckExist = Truck::find($request->truck_id);
        $findCommand = Trip::where('sales_purchasing_requsets_id',$requestId)->get()->first();
        if($findCommand != null && $requestExist!=null)
            return  response()->json(["status"=>false, "message"=>"تم اعطاء امر الرحلة مسبقا"]);
        if($requestExist!=null){
            if($driverExist != null){
                $stateDriver = $driverExist->state;
                if($stateDriver == "متاح"){
                    if($truckExist != null){
                        $statetruck = $truckExist->state;
                    if($statetruck == "متاحة")
                        return $next($request);
                    else
                    return  $this -> returnError('error', ' الشاحنة غير متاحة');
                }
                else
                return  $this -> returnError('error', ' الشاحنة غير متوفرة');
            }
                else
                return  $this -> returnError('error', ' السائق غير متاح');
        }
        else
                return  $this -> returnError('error', ' السائق غير متوفر');
    }
    return  $this -> returnError('error', 'الطلبية غير متوفرة');
}
}
