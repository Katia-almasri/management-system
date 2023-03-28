<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\SellingPort;
use App\Models\SellingOrder;
use Auth;

class SellingPortController extends Controller
{
    use validationTrait;

    public function displaySellingPort(Request $request){
        $SellingPort = SellingPort::all();
        return response()->json($SellingPort, 200);
    }

    public function displaySellingOrder(Request $request){
        $SellingOrder = SellingOrder::with('sellingPort','sellingOrderDetails')->get();
        return response()->json($SellingOrder, 200);
    }

    public function SoftDeleteSellingPort(Request $request, $SellingId){
        SellingPort::find($SellingId)->delete();
       return  response()->json(["status"=>true, "message"=>"selling port soft Deleted successfully"]);
   }

   public function restoreSellingPort(Request $request, $SellingId)
   {
        SellingPort::withTrashed()->find($SellingId)->restore();
       return  response()->json(["status"=>true, "message"=>"Restore Deleted successfully"]);
   }

   public function SellingPortTrashed(Request $request)
   {
       $SellingPortTrashed = SellingPort::onlyTrashed()->get();
       return response()->json($SellingPortTrashed, 200);
   }



}
