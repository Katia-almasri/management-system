<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\weightAfterArrivalDetectionDetail;

class isApprovedMaterial
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {

        $detailCommandId = $request->detailCommandId;
        $detailDetectionExist = weightAfterArrivalDetectionDetail::find($detailCommandId);
        if($detailDetectionExist!=null){
            if($detailDetectionExist->approved_at != null){
                return  $this -> returnError('error', 'تم خرج كل مواد الكشف');
            }
            return $next($request);
        }
        return  $this -> returnError('error', 'الكشف غير موجود');
    }
}
