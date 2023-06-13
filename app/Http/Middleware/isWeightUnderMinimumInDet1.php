<?php

namespace App\Http\Middleware;

use App\Models\DetonatorFrige1;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWeightUnderMinimumInDet1
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $det1 = DetonatorFrige1::with('warehouse')->where('id', $_detail['det_id'])->get()->first();
            if(($det1->warehouse->minimum!=null && $_detail['weight']>=$det1->warehouse->minimum) || ($det1->warehouse->stockpile !=null && $_detail['weight']>=$det1->warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);
    }
}
