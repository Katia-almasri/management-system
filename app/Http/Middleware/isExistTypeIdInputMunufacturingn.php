<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\InputManufacturing;

class isExistTypeIdInputMunufacturingn
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
        $type_id = $request->type_id;
        $findInput = InputManufacturing::where('type_id',$type_id)->get()->first();
        if(!is_null($findInput ) )
            return $next($request);
        return  $this -> returnError('error', 'النوع غير متوفر');
    }
}
