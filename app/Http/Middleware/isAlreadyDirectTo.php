<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\outPut_SlaughterSupervisor_detail;

class isAlreadyDirectTo
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
        foreach($request->details as $_det){
            $detailId = $_det['id'];
            $outputDetailExist = outPut_SlaughterSupervisor_detail::find($detailId);
            if($outputDetailExist != null){
                if($outputDetailExist->direct_to != null){
                    $counter1 ++;
                    return  $this -> returnError('error', 'تم توجيه الخرج لقسم معين مسبقا');
                }
            }
            else{
                return  $this -> returnError('error', 'الكشف غير موجود');
                $counter2 ++;
            }

        }
        if($counter1 == 0 && $counter2 == 0 )
            return $next($request);
    }

}
