<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\Driver;

class isDeletedDriverExist
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
        $driverId = $request->driverId;
        $driverExist = Driver::withTrashed()->find($driverId);
        if($driverExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'the driver does not exist');
    }
}
