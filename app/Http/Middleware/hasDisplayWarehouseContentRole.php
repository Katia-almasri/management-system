<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class hasDisplayWarehouseContentRole
{
   use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $userPermission = $request->user()->isAbleTo('warehouse-read');
        if($userPermission==true) {
            return $next($request);
        }    
        return $this->returnError('error', 'عذراً ليس لديك السماحية لاستعراض محتويات المخازن');
    }
}
