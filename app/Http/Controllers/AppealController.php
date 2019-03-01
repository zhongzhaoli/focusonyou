<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    public function store(Request $request){
        $id = time() . md5(uniqid());
        $request->merge(["create_time" => date("Y-m-d H:i:s"), "id" => $id]);
        $result = Validator::make($request->all(),[
            "story" => "required",
        ],[
            "story.required" => "故事不能为空",
        ]);
        if($result->fails()){
            return response()->json(['message' => "插入失败"], 400);            
        }
        $operation = DB::table("appeal")->insert([
            'id' => $request['id'],
            'nickname' => $request['nickname'],
            'music' => $request['music'],
            'story' => $request['story'],
            'create_time' => $request['create_time']
        ]);
        if($operation){
            return response()->json(['message' => "插入成功"], 200);
        }
        else{
            return response()->json(['message' => "插入失败"], 400);
        }
    }
}
