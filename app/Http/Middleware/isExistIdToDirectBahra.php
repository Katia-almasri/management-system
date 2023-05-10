<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\outPut_SlaughterSupervisor_detail;

class isExistIdToDirectBahra
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
        $findOutputDet = outPut_SlaughterSupervisor_detail::where('direct_to_bahra',0)->get()->first();
        if(!is_null($findOutputDet))
            return $next($request);
        return  $this -> returnError('error', 'لا يوجد خرج مذبوح');
    }
}
