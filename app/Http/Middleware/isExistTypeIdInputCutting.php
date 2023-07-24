<?php

namespace App\Http\Middleware;
use App\Traits\validationTrait;
use App\Models\InputCutting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class isExistTypeIdInputCutting
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
        $findInput = DB::table('input_cuttings')
            ->join('output_production_types', 'input_cuttings.type_id', '=', 'output_production_types.id')
            ->select('input_cuttings.type_id','output_production_types.type', DB::raw('SUM(weight) as weight'))
            ->where([['output_citting_id',null],['type_id',$type_id]])->groupBy('type_id','output_production_types.type')->get()->first();
            $findInputCutting = InputCutting::where([['type_id', $type_id],['output_citting_id',null]])->get();
            $totalInput = 0;
            $totalOutput = 0;
            $totalOutputRemnat = 0;

            if(!is_null($findInput)){
                foreach($findInputCutting as $_findInputCutting){
                    $totalInput += $_findInputCutting->weight;
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


