<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PurchaseOffer;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;

class isFarmUsed
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
        $FarmId = $request->FarmId;
        $PurchaseOfferExistFarm = PurchaseOffer::where('farm_id',$FarmId)->get()->first();
        $salesPurchasingRequsetExistFarm = salesPurchasingRequset::where('farm_id',$FarmId)->get()->first();
        if($PurchaseOfferExistFarm!=null)
            return  $this -> returnError('error', 'لا يمكن حذف المزرعة لوجود عرض منها');
        if($salesPurchasingRequsetExistFarm!=null)
            return  $this -> returnError('error', 'لا يمكن حذف المزرعة لوجود طلب شراء منها');
        return $next($request);
    }
}
