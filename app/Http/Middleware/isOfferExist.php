<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PurchaseOffer;
use App\Traits\validationTrait;

class isOfferExist
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $offer = PurchaseOffer::find($request->offerId);
        if($offer!=null)
            return $next($request);
        return  $this -> returnError('error', 'هذا العرض غير موجود');
    }
}
