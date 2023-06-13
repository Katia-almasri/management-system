<?php

namespace App\Http\Middleware;

use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWeightUnderMinimumInWarehouse
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $warehouse = Warehouse::find($_detail['warheouse_id']);
            if(($warehouse->minimum!=null && $_detail['weight']>=$warehouse->minimum) || ($warehouse->stockpile !=null && $_detail['weight']>=$warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);

    }
}
