<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Trip;
use App\Traits\validationTrait;

class isDriverUsed
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
        $driverId = $request->DriverId;
        $driverExist = Trip::where('driver_id',$driverId)->get()->first();
        if($driverExist!=null)
            return  $this -> returnError('error', 'لا يمكن حذف السائق لأنه مستخدم في الرحلة');
        return $next($request);
    }
}
