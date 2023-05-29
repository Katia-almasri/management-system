<?php

namespace App\Http\Middleware;

use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isWarehouseIdExist
{
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {
        $warehouseId = $request->warehouseId;
        $warehouseExist = Warehouse::find($warehouseId);
        if($warehouseExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'عذراً المادة غير متوفرة في المخزن');
    }
}
