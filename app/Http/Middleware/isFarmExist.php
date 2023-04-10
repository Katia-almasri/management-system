<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Farm;
use App\Traits\validationTrait;

class isFarmExist
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
        $farmId = $request->FarmId;
        $isExistFarm = Farm::find($farmId);
        if($isExistFarm!=null)
            return $next($request);
        return  $this -> returnError('error', 'المزرعة غير متوفرة');
    }
}
