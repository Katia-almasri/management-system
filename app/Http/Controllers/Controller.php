<?php

namespace App\Http\Controllers;

use App\Models\product;
use App\Models\RowMaterial;
use App\Models\sellingortype;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Manager;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use validationTrait;

    public function Login(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->all()]);
        }

        if(auth()->guard('managers')->attempt(['username' => request('username'), 'password' => request('password')])){

            config(['auth.guards.api.provider' => 'managers']);

            $user = Manager::select('*')->find(auth()->guard('managers')->user()->id);
            $success =  $user;
            $success['token'] =  $user->createToken('api-token', ['managers'])->accessToken;

            return response()->json($success, 200);
        }else{
            return response()->json(['error' => ['UserName and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
         return response()->json([
           'message' => 'logged out successfully'
         ]);
    }

    public function getRowMaterial(Request $request){
        $rowMaterials = RowMaterial::get();
        return response()->json($rowMaterials);
    }

    public function getProducts(Request $request){
        $products = product::get();
        return response()->json($products);
    }

    public function getSellingPortType(Request $request){
        $sellingPortTypes = sellingortype::get();
        return response()->json($sellingPortTypes);
    }


}
