<?php

namespace App\Http\Controllers;

use App\Models\Direction;
use App\Models\Governate;
use App\Models\WarehouseType;

use App\Models\PoultryReceiptDetection;
use App\Models\product;
use App\Models\RowMaterial;
use App\Models\sellingortype;
use App\Models\Warehouse;
use App\Models\RemnantsType;
use App\Models\outPut_Type_Production;
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

    public function Login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        //////////// attempt :) /////////////////
        $manager = Manager::where('username', $request->username)->get()->first();

        if ($manager != null && decrypt($manager->password) == $request->password) {
            // if(auth()->guard('managers')->attempt(['username' => request('username'), 'password' => request('password')])){
            config(['auth.guards.api.provider' => 'managers']);

            // $user = Manager::select('*')->find(auth()->guard('managers')->user()->id);
            if ($manager->date_of_leave != null)
                return response()->json([
                    'message' => 'عذراً يرجى مراجعة المدير التنفيذي'
                ]);
            Auth::guard('managers')->login($manager);
            $success = $manager;
            $success['token'] = $manager->createToken('api-token', ['managers'])->accessToken;

            return response()->json($success, 200);
        } else {
            return response()->json(['error' => ['UserName and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'logged out successfully'
        ]);
    }

    //////////////////////  users profile and editiona /////////////////////
    public function displayMyProfile(Request $request)
    {
        $user = Manager::find($request->user()->id);
        $myProfileData = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'username' => $user->username,
            'password' => $decryptedPassword = decrypt($user->password)
        ];

        return response()->json($myProfileData);
    }

    public function editMyProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'username' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->all()]);
        }
        $user = Manager::find($request->user()->id);
        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => encrypt($request->password)
        ]);
        return response()->json(["status"=>true, "message"=>"تم تعديل بياناتك بنجاح"]);
    }

    public function getRowMaterial(Request $request)
    {
        $rowMaterials = RowMaterial::get();
        return response()->json($rowMaterials);
    }

    public function getProducts(Request $request)
    {
        $products = outPut_Type_Production::get();
        return response()->json($products);
    }

    public function getSellingPortType(Request $request)
    {
        $sellingPortTypes = sellingortype::get();
        return response()->json($sellingPortTypes);
    }

    public function getWeightAfterArrival(Request $request, $recieptId)
    {
        $weightAfterArrivalDetection = PoultryReceiptDetection::where('id', $recieptId)
            ->with([
                'PoultryReceiptDetectionDetails' => function ($q) {
                    $q->with('rowMaterial');
                },
                'weightAfterArrivalDetection' => function ($q) {
                    $q->with('weightAfterArrivalDetectionDetail');
                },
                'farm'
            ])
            ->get();
        //CHECK THE ROLE USER
        //IF THE USER IS SALES MANAGER THEN UPDATE is_seen_by_sales_manager (to update the count of notifications)
        //ELSE DO NOT SO ANYTHING

        if ($request->user()->hasRole('Purchasing-and-Sales-manager'))
            PoultryReceiptDetection::where('id', $recieptId)->update(['is_seen_by_sales_manager' => 1]);

        return response()->json($weightAfterArrivalDetection);
    }

    public function displayWarehouseContent(Request $request)
    {
        $warehouseContent = Warehouse::where([['tot_weight', '!=', 0], ['tot_weight', '!=', null]])->with('outPut_Type_Production')->get();
        return response()->json($warehouseContent);
    }
    ///////////////////// drop down //////////////////////
    public function dropDownFromLake(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'بحرات')->get();
        return response()->json($directions);
    }

    public function dropDownFromZero(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'براد صفري')->get();
        return response()->json($directions);
    }

    public function dropDownFromManufactoring(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'تصنيع')->get();
        return response()->json($directions);
    }

    public function dropDownFromCutting(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'التقطيع')->get();
        return response()->json($directions);
    }

    public function dropDownFromDet1(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'صاعقة 1')->get();
        return response()->json($directions);
    }

    public function dropDownFromDet2(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'صاعقة 2')->get();
        return response()->json($directions);
    }

    public function dropDownFromDet3(Request $request)
    {
        $directions = Direction::select('to')->where('section', 'صاعقة 3')->get();
        return response()->json($directions);
    }

    public function getProductionCommandsDropDown(Request $request)
    {
        $warehouType = WarehouseType::limit(2)->get();
        return response()->json($warehouType);
    }

    public function getGovernateDropDown(Request $request)
    {
        $governateId = Governate::get();
        return response()->json($governateId);
    }

    public function displayRemnantsType(Request $request){
        $RemnantsType = RemnantsType::get();
        return response()->json($RemnantsType);
    }



}
