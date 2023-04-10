<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\SellingPort;
use App\Models\Manager;
use App\Models\SellingOrder;
use App\Models\Contract;
use App\Models\ContractDetail;
use App\Models\SellingOrderDetail;
use Auth;
use Carbon\Carbon;
use File;

class ContractController extends Controller
{
    use validationTrait;


public function addRequestContract(Request $request){
    $validator = Validator::make($request->all(), [
        'contract_type' => 'required',

    ]);

    if($validator->fails()){
        return response()->json(['error' => $validator->errors()->all()]);
    }

    $RequestContract = new Contract();
    $RequestContract->selling_port_id = $request->user()->id;
    $RequestContract->contract_type = $request->contract_type;
    $RequestContract->save();
    //NOW THE DETAILS
    foreach($request->details as $_detail){
      $contractDetails = new ContractDetail();
      $contractDetails->contract_id = $RequestContract->id;
      $contractDetails->amount = $_detail['amount'];
      $contractDetails->type = $_detail['type'];
      $contractDetails->save();
    }

    return response()->json(["status"=>true, "message"=>"request contract successfully"]);
}

public function getContracts(Request $request){
    $contracts = Contract::orderBy('id', 'DESC')->get();
    return response()->json($contracts, 200);
}

public function getContractRequestDetail(Request $request,$contractId){
    $contractFind = Contract::where('id',$contractId)->with('contractDetails')->get();
    return response()->json($contractFind, 200);
}

public function AcceptContractRequest(Request $request,$contractId){
    $contractFind = Contract::where('id',$contractId)->update(['accept'=>1]);
    return response()->json(["status"=>true, "message"=>"تمت الموافقة"]);
}



}



