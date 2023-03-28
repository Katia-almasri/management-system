<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;

class isSalesPurchaseExist
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
        $salesPurchaseId = $request->RequestId;
        $isExistSalesPurchase = salesPurchasingRequset::find($salesPurchaseId);
        if($isExistSalesPurchase!=null)
            return $next($request);
        return  $this -> returnError('error', 'طلب الشراء أو المبيع غير متواجد');

    }
}
