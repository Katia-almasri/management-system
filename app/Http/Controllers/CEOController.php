<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Hash;
use Illuminate\Http\Request;
use App\Traits\validationTrait;
use Validator;
use App\Models\Manager;
use Auth;

class CEOController extends Controller
{
    use validationTrait;

    public function CEOLogin(Request $request) {
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
            $success['token'] =  $user->createToken('api-token')->accessToken;

            return response()->json($success, 200);
        }else{
            return response()->json(['error' => ['Email and Password are Wrong.']], 200);
        }
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
         return response()->json([
           'message' => 'logged out successfully'
         ]);
    }

    public function getManagingLevel(Request $request){
        $managing_levels = Manager::pluck('managing_level');
        return response()->json($managing_levels);
    }

    public function addUser(Request $request){

        //search
        $oldManager = MAnager::where('managing_level', $request->managing_level)->latest('id')->first();
        $oldManager->update(['date_of_leave'=>Carbon::now()]);
        $manager = new Manager();
        $manager->managing_level = $request->managing_level;
        $manager->first_name = $request->first_name;
        $manager->last_name = $request->last_name;
        $password = $request->first_name.'123456';
        $manager->password = Hash::make($password);
        $manager->username = $request->username;
        $manager->date_of_hiring = Carbon::now();
        $manager->save();

        return response()->json([
            'message' =>' تم إضافة '.$request->managing_level.' جديد'
          ]);
    }




}
