<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWarehouseSupervisor
{
    use validationTrait;
   
    public function handle(Request $request, Closure $next)
    {

        if($request->user()->hasRole('warehouse_supervisor'))
            return $next($request);
         return  $this -> returnError('error', 'you don`t have the role ');
    }
}
