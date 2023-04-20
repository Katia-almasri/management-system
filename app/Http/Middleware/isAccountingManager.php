<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class isAccountingManager
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    use validationTrait;
    public function handle(Request $request, Closure $next)
    {

     if($request->user()->hasRole('Accounting-Manager'))
            return $next($request);
         return  $this -> returnError('error', 'you don`t have the role');
    }
}
