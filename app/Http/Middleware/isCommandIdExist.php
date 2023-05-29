<?php

namespace App\Http\Middleware;

use App\Models\Command;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isCommandIdExist
{
    use validationTrait;
   
    public function handle(Request $request, Closure $next)
    {
        $commandId = $request->commandId;
        $commandExist = Command::find($commandId);
        if($commandExist!=null)
            return $next($request);
        return  $this -> returnError('error', 'عذراً هذا الأمر غير موجود');
    }
}
