<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Traits\validationTrait;

class is_truck_used
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
        $truckId = $request->TruckId;
        $truckExist = Trip::where('truck_id',$truckId)->get()->first();
        if($truckExist!=null)
            return  $this -> returnError('error', 'لا يمكن حذف الشاحنة لأنها مستخدمة في الرحلة');
        return $next($request);
    }
}
