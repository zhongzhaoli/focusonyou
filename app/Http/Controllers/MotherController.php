<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Validator;

class MotherController extends Controller
{
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'nickname' => 'required|Max:255',
            'phone' => 'required|regex:/^1[345789][0-9]{9}$/',
            'remark' => 'Max:255',
            'type' => 'required|Integer'
        ],[
            'nickname.required' => '称呼不能为空哦',
            'nickname.Max' => '称呼太长了',
            'phone.required' => '手机号不能为空哦',
            'phone.regex' => '手机号不正确哦',
            'remark.Max' => '想说的话太长了',
            'type.required' => '类型不能为空哦',
            'type.Integer' => '类型不正确哦',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);            
        }
        else{
            $a = DB::table("mother_message")->insert([
                "id" => time() . md5(uniqid()),
                "nickname" => $request->get("nickname"),
                "phone" => $request->get("phone"),
                "remark" => $request->get("remark"),
                "create_time" => date("Y-m-d H:i:s"),
                "type" => $request->get("type")
            ]);
            if($a){
                return response()->json(["message" => "制定成功"], 200);
            }
            else{
                return response()->json(["message" => "制定失败"], 400);
            }
        }
    }
}
