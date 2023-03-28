<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Farm;
use App\Models\PurchaseOffer;
use Auth;

class FarmController extends Controller
{
    use validationTrait;

    public function displayFarms(Request $request){
        $Farm = Farm::all();
        return response()->json($Farm, 200);
    }

    public function displayPurchaseOffers(Request $request){
        $PurchaseOffer = PurchaseOffer::with('farm','detailpurchaseOrders')->get();
        return response()->json($PurchaseOffer, 200);
    }

    public function SoftDeleteFarm(Request $request, $FarmId){
        Farm::find($FarmId)->delete();
       return  response()->json(["status"=>true, "message"=>"farm soft Deleted successfully"]);
   }

   public function restoreFarm(Request $request, $FarmId)
   {
        Farm::withTrashed()->find($FarmId)->restore();
       return  response()->json(["status"=>true, "message"=>"Restore Deleted successfully"]);
   }

   public function FarmTrashed(Request $request)
   {
       $FarmTrashed = Farm::onlyTrashed()->get();
       return response()->json($FarmTrashed, 200);
   }


}
