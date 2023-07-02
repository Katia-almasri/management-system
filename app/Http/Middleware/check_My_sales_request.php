<?php

namespace App\Http\Middleware;

use App\Models\salesPurchasingRequset;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class check_My_sales_request
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
        $SalesPurchasingRequest = salesPurchasingRequset::where('id',$RequestId)
        ->where('selling_port_id','=',$request->user()->id)->get()->first();


        if($SalesPurchasingRequest != null)
            return $next($request);
        return  $this -> returnError('error', 'لا تملك هذا الطلب');
    }
}
