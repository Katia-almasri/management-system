<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\Truck;

class isTruckExist
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
        $truckExist = Truck::find($truckId);
        if($truckExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'the truck does not exist');
    }
}
