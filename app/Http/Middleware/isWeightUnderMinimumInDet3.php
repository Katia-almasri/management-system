<?php

namespace App\Http\Middleware;

use App\Models\DetonatorFrige3;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWeightUnderMinimumInDet3
{
    use validationTrait;
    
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $det3 = DetonatorFrige3::with('warehouse')->where('id', $_detail['det_id'])->get()->first();
            if(($det3->warehouse->minimum!=null && $_detail['weight']>=$det3->warehouse->minimum) || ($det3->warehouse->stockpile !=null && $_detail['weight']>=$det3->warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);
    }
}
