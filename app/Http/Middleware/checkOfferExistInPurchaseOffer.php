<?php

namespace App\Http\Middleware;

use App\Models\PurchaseOffer;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class checkOfferExistInPurchaseOffer
{
   use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $offerExist = PurchaseOffer::find($request->offerId);
        if($offerExist!=null) {
            return $next($request);
        }    
        return $this->returnError('error', 'عذراَ العرض غير موجود');

    }
}
