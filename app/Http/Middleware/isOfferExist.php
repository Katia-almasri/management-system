<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PurchaseOffer;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;

class isOfferExist
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $offerExistInRequest = salesPurchasingRequset::where('offer_id',$request->offerId)->get()->first();
        $offer = PurchaseOffer::find($request->offerId);
        if($offer!=null)
            if($offerExistInRequest != null)
                return  $this -> returnError('error', 'تم تأكيد العرض مسبقا');
            return $next($request);
        return  $this -> returnError('error', 'هذا العرض غير موجود');
    }
}
