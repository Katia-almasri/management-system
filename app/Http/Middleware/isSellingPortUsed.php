<?php

namespace App\Http\Middleware;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;

use Closure;
use Illuminate\Http\Request;

class isSellingPortUsed
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
        $sellingPortId = $request->sellingPortId;
        $salesPurchasingRequsetExistSellingPort = salesPurchasingRequset::where('selling_port_id',$sellingPortId)->get()->first();
        if($salesPurchasingRequsetExistSellingPort!=null)
            return  $this -> returnError('error', 'لا يمكن حذف منفذ بيع لأنه قدم طلب مسبقا');
        return $next($request);
    }
}
