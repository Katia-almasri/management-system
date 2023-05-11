<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\InputManufacturing;

class isExistInputMunufacturing
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
        $counter1 = 0;
        $counter2 = 0;
        foreach($request->ids as $_id){
            $InputId = $_id['id'];
            $InputExistId = InputManufacturing::find($InputId);
            if($InputExistId!=null){
                if(!is_null($InputExistId->manufacturings_done) ){
                    $counter1 ++;
                }
            }
            else{
                return  $this -> returnError('error', 'الدخل غير موجود');
                $counter2 ++;
            }

        }
        if($counter1 != 0 )
            return  $this -> returnError('error', 'تم اعطاء الامر مسبقا');
        if($counter1 == 0 && $counter2 == 0)
            return $next($request);
    }

}
