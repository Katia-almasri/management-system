<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\Farm;

class isDeletedFarmExist
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
        $deletedFarmExist = Farm::withTrashed()->find($farmId);
        if($deletedFarmExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'المزرعة غير محذوفة أو غير متواجدة أصلاً');
    }
}
