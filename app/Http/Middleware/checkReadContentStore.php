<?php

namespace App\Http\Middleware;

use App\Models\PoultryReceiptDetection;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class checkReadContentStore
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $userPermission = $request->user()->isAbleTo('content store-read');
        if($userPermission==true) {
            return $next($request);
        }
        return $this->returnError('error', 'عذراً ليس لديك السماحية لاستعراض تفاصيل الشحنات بعد وزنها');
    }
}
