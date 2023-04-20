<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;

class isRequestExist
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
        $RequestId = $request->RequestId;
        $isExistRequest = salesPurchasingRequset::find($RequestId);
        if($isExistRequest!=null)
            if($isExistRequest->accept_by_sales == 1)
                if($isExistRequest->accept_by_ceo == null | $isExistRequest->accept_by_ceo == 0)
                    return $next($request);
                    else
                    return  $this -> returnError('error', 'تمت الموافقة مسبقا');
            else
                return  $this -> returnError('error', 'لم تتم الموافقة من قبل مدير المشتريات');
        return  $this -> returnError('error', 'الطلب غير متوفر');
    }
}
