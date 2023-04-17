<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\PoultryReceiptDetection;
class isRecieptWeighted
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $recieptId = $request->recieptId;
        $isRecieptWeighted = PoultryReceiptDetection::find($recieptId);
         if ($isRecieptWeighted->is_weighted_after_arrive == 0)
            return $this->returnError('error', 'هذا الكشف لم يتم وزنه بعد');
        return $next($request);
    }
}
