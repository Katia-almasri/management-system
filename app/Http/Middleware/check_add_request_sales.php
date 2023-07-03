<?php

namespace App\Http\Middleware;
use App\Models\outPut_Type_Production;
use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;

class check_add_request_sales
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
        if($request->request_type == 1){
            foreach($request->details as $_detail){
                $typaName = outPut_Type_Production::where('type',$_detail['type'])->get();
                $warehouseContent = Warehouse::where('type_id',$typaName[0]->id)->with('outPut_Type_Production')->get()->first();
                if($_detail['amount'] > $warehouseContent["tot_weight"])
                    return $this->returnError('error', 'عذراَ الكمية المطلوبة غير متوفرة في المسودعات');
                }
            }
        return $next($request);
    }
}
