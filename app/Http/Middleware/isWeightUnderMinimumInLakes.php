<?php

namespace App\Http\Middleware;

use App\Models\Lake;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
class isWeightUnderMinimumInLakes
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $details = $request->details;
        foreach ($details as $_detail) {
            
            $lake = Lake::with('warehouse')->where('id', $_detail['lake_id'])->get()->first();
            // return response()->json($lake->weight - $_detail['weight']);
            if(($lake->warehouse->minimum!=null && $lake->weight - $_detail['weight']  < $lake->warehouse->minimum) || ($lake->warehouse->stockpile !=null &&  $lake->weight - $_detail['weight'] < $lake->warehouse->stockpile)){
                return  $this -> returnError('error', 'الوزن المخرج أكبر من الوزن الاحتياطي');
            }
        }
        return $next($request);
    }
}
