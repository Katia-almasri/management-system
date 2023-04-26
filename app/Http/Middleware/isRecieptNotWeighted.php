<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\PoultryReceiptDetection;

class isRecieptNotWeighted
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $recieptId = $request->recieptId;
        $isRecieptWeighted = PoultryReceiptDetection::find($recieptId);
         if ($isRecieptWeighted->is_weighted_after_arrive == 1)
            return $this->returnError('error', 'هذا الكشف تم وزنه مسبقاً');
        return $next($request);
    }
}
