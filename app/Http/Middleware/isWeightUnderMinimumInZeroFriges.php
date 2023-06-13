<?php

namespace App\Http\Middleware;

use App\Models\ZeroFrige;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWeightUnderMinimumInZeroFriges
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $zeroFrige = ZeroFrige::with('warehouse')->where('id', $_detail['zero_id'])->get()->first();
            if(($zeroFrige->warehouse->minimum!=null && $_detail['weight']>=$zeroFrige->warehouse->minimum) || ($zeroFrige->warehouse->stockpile !=null && $_detail['weight']>=$zeroFrige->warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);
    }
}
