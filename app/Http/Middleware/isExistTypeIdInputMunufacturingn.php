<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\InputManufacturing;
use Illuminate\Support\Facades\DB;

class isExistTypeIdInputMunufacturingn
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
        $type_id = $request->type_id;
        $findInput = DB::table('input_manufacturings')
        ->join('output_production_types', 'input_manufacturings.type_id', '=', 'output_production_types.id')
        ->select('input_manufacturings.type_id','output_production_types.type', DB::raw('SUM(weight) as weight'))
        ->where([['output_manufacturing_id',null],['type_id',$type_id]])->groupBy('type_id','output_production_types.type')->get()->first();

        $findInputManufacturing = InputManufacturing::where([['type_id', $type_id],['output_manufacturing_id',null]])->get();
        $totalInput = 0;
        $totalOutput = 0;
        $totalOutputRemnat = 0;

        if(!is_null($findInput)){
            foreach($findInputManufacturing as $_findInputManufacturing){
                $totalInput += $_findInputManufacturing->weight;
            }
            foreach ($request->details as $_detail) {
                $totalOutput += $_detail['weight'];
            }

            foreach($request->details_remnat as $_details_remnat){
                $totalOutputRemnat += $_details_remnat['weight'];
            }
            if($totalInput < $totalOutput + $totalOutputRemnat)
            {
                return  $this -> returnError('error', 'الدخل اصغر من الخرج');
            }
            return $next($request);
        }

        return  $this -> returnError('error', 'النوع غير متوفر');
    }
}
