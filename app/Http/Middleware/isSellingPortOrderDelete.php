<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\salesPurchasingRequset;
use App\Traits\validationTrait;
use App\Models\SellingOrder;

class isSellingPortOrderDelete
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
        $SellingPortOrderId = $request->SellingPortOrderId;
        $isExistSellingPortOrder = salesPurchasingRequset::
        where([['id',$SellingPortOrderId],['selling_port_id',$request->user()->id],
        ['accept_by_ceo',Null],['accept_by_sales',Null]])->first();
        if($isExistSellingPortOrder!=null )
            return $next($request);
        return  $this -> returnError('error', ' طلبية منفذ البيع غير متواجدة أو تمت الموافقة من قبل المسؤولين');
    }
}
