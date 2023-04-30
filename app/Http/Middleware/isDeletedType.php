<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\outPut_SlaughterSupervisorType_table;

class isDeletedType
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
        $typeId = $request->typeId;
        $typeExist = outPut_SlaughterSupervisorType_table::find($typeId);
        if($typeExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'النوع غير متوفر');
    }
}
