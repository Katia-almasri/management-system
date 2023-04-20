<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\PoultryReceiptDetection;

class checkRecieptId
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $recieptId = $request->recieptId;
        $isRecieptExists = PoultryReceiptDetection::find($recieptId);
        if ($isRecieptExists == null)
            return $this->returnError('error', 'رقم الكشف غير موجود');
        return $next($request);
    }
}