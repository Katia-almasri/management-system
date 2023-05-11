<?php

namespace App\Http\Middleware;
use App\Traits\validationTrait;
use App\Models\InputCutting;
use Closure;
use Illuminate\Http\Request;

class isExistTypeIdInputCutting
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
        $findInput = InputCutting::where([['type_id',$type_id],['cutting_done',0]])->get()->first();
        if(!is_null($findInput ) )
            return $next($request);
        return  $this -> returnError('error', 'النوع غير متوفر');
    }
}
