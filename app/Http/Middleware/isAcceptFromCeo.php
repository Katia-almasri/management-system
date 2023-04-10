<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\salesPurchasingRequset;

class isAcceptFromCeo
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
        $requestAccept = salesPurchasingRequset::find($RequestId);
        if($requestAccept!=null)
            if($requestAccept->accept_by_ceo==1 && $requestAccept->accept_by_sales==1)
                return $next($request);
        if($requestAccept->accept_by_ceo==0 && $requestAccept->accept_by_sales==1)
            return  $this -> returnError('error', 'الطلب في انتظار موافقة المدير التنفيذي');
        if($requestAccept->accept_by_ceo==1 && $requestAccept->accept_by_sales==0)
            return  $this -> returnError('error', 'الطلب في انتظار موافقة مدير المشتريات والمبيعات');
        if($requestAccept->accept_by_ceo==0 && $requestAccept->accept_by_sales==0)
            return  $this -> returnError('error', ' الطلب في انتظار موافقة مدير المشتريات والمبيعات وموافقة المدير التنفيذي');
        return  $this -> returnError('error', 'طلب الشراء أو المبيع غير متواجد');
    }
}
