<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use App\Models\weightAfterArrivalDetectionDetail;

class isCurrentWeightAndId
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
        $counter1 = 0;
        $counter2 = 0;
        $counter3 = 0;
        foreach($request->details_id as $_id){
            $detailCommandId = $_id['id'];
            $detailDetectionDetailExist = weightAfterArrivalDetectionDetail::find($detailCommandId);
            if($detailDetectionDetailExist!=null){
                if($detailDetectionDetailExist->approved_at != null){
                    $counter1 ++;
                    return  $this -> returnError('error', 'تم خرج كل مواد الكشف');
                }
                if($detailDetectionDetailExist->current_weight < $_id['weight'])
                {
                    $counter3 ++;
                    return  response()->json(["status"=>false, "message"=>"الوزن المدخل أكبر من الوزن الموجود عند امر القبان"]);
                }
            }
            else{
                return  $this -> returnError('error', 'الكشف غير موجود');
                $counter2 ++;
            }

        }
        if($counter1 == 0 && $counter2 == 0 && $counter3 == 0)
            return $next($request);
    }
    }

