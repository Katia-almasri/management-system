<?php

namespace App\Http\Middleware;
use App\Traits\validationTrait;

use Closure;
use Illuminate\Http\Request;

class hasDisplayCommandsToWarehouseRole
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $userPermission = $request->user()->isAbleTo('commands for warehouse-read');
        if($userPermission==true) {
            return $next($request);
        }    
        return $this->returnError('error', 'عذراً ليس لديك السماحية لاستعراض أوامر مدير الإنتاج إلى المخازن');

    }
}
