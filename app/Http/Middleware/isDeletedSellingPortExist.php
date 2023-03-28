<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\SellingPort;

class isDeletedSellingPortExist
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
        $sellingPortId = $request->SellingId;
        $deletedSellingPortExist = SellingPort::withTrashed()->find($sellingPortId);
        if($deletedSellingPortExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'منفذ البيع غير محذوف أو غير متواجد أصلاً');
    }
}
