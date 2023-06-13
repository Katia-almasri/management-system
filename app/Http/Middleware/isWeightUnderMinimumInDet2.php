<?php

namespace App\Http\Middleware;

use App\Models\DetonatorFrige2;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWeightUnderMinimumInDet2
{
    use validationTrait;
    
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $det2 = DetonatorFrige2::with('warehouse')->where('id', $_detail['det_id'])->get()->first();
            if(($det2->warehouse->minimum!=null && $_detail['weight']>=$det2->warehouse->minimum) || ($det2->warehouse->stockpile !=null && $_detail['weight']>=$det2->warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);
    }
}
