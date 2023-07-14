<?php

namespace App\Http\Middleware;
use App\Models\outPut_Type_Production;
use App\Models\Warehouse;
use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Illuminate\Support\Facades\DB;

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

                $typeSalesRequest = DB::table('sales-purchasing-requset-details')
                ->join('sales_purchasing_requests', 'sales_purchasing_requests.id', '=', 'sales-purchasing-requset-details.requset_id')
                ->select('type', DB::raw('SUM(amount) as weight'))
                ->where([['sales_purchasing_requests.accept_by_ceo',null],['type',$_detail['type']]])->groupBy('type')->get();

                if($warehouseContent->tot_weight - $typeSalesRequest[0]->weight < 0 )
                    return $this->returnError('error', 'عذرا الكمية في الانتظار');


                }

            }

        return $next($request);
    }
}
