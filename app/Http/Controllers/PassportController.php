<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{

 
    public $successStatus = 200;
 
    /**
     * login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(){
        if(Auth::attempt(['name' => request('name'), 'password' => request('password')])){
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['message' => $success], $this->successStatus);
        }
        else{
            return response()->json(['error'=>'Unauthorised'], 400);
        }
    }

 
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ],[
            "name.required" => "用户名不能为空",
            "password.required" => "密码不能为空",
            "c_password.required" => "密码不能为空",
            "c_password.same" => "两次密码必须相同"
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);            
        }
 
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;
 
        return response()->json(['success'=>$success], $this->successStatus);
    }
 
    /**
     * details api
     *
     * @return \Illuminate\Http\Response
     */
    public function getDetails()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

}
