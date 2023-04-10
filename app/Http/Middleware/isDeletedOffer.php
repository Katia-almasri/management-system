<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\PurchaseOffer;

class isDeletedOffer
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
        $offerId = $request->offerId;
        $deletedOffer = PurchaseOffer::where([['id',$offerId],['farm_id',$request->user()->id]])->first();
        if($deletedOffer!=null)
            return $next($request);
        return  $this -> returnError('error', 'الطلب غير متوفر');
    }
}
