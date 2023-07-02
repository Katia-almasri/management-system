<?php

namespace App\Http\Middleware;

use App\Models\input_slaughter_table;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class check_Input_Slaughter
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
        $InputSlaughters = input_slaughter_table::where('output_date',null)->sum('weight');
        // return response($InputSlaughters);
        $totalWeight = 0;
        foreach($request->details as $_detail){
            $totalWeight += $_detail['weight'];
        }
        if($InputSlaughters <= 0 )
            return  $this -> returnError('error', 'لا يوجد دخل');
        if($totalWeight > $InputSlaughters )
            return  $this -> returnError('error', 'الدخل أكبر من الخرج');
        return $next($request);
    }
}
