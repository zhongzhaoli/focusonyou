<?php

namespace App\Http\Controllers;

use Validator;
use DB;
use Illuminate\Http\Request;

class AppealController extends Controller
{
    public function show($table_name){
        $a = DB::table($table_name)->orderBy("create_time", 'desc')->get();
        return $a;
    }
    public function store(Request $request){
        $id = time() . md5(uniqid());
        $request->merge(["create_time" => date("Y-m-d H:i:s"), "id" => $id]);
        if($request->get("type") == 1){
            $result = Validator::make($request->all(),[
                "story" => "required",
            ],[
                "story.required" => "故事不能为空",
            ]);
            if($result->fails()){
                return response()->json(['message' => "故事不能为空"], 400);            
            }
            $operation = DB::table("appeal")->insert([
                'id' => $request['id'],
                'nickname' => $request['nickname'],
                'music' => $request['music'],
                'story' => $request['story'],
                'contact' => $request['contact'],
                'create_time' => $request['create_time']
            ]);
            if($operation){
                return response()->json(['message' => "收到，欢迎下次上诉"], 200);
            }
            else{
                return response()->json(['message' => "上诉失败"], 400);
            }
        }
        else{
            $result = Validator::make($request->all(),[
                "type_text" => "required",
            ],[
                "story.required" => "类型不能为空",
            ]);
            if($result->fails()){
                return response()->json(['message' => "类型不能为空"], 400);            
            }
            $operation = DB::table("proposal")->insert([
                'id' => $request['id'],
                'nickname' => $request['nickname'],
                'type_text' => $request['type_text'],
                'proposal' => $request['proposal'],
                'contact' => $request['contact'],
                'create_time' => $request['create_time']
            ]);
            if($operation){
                return response()->json(['message' => "收到，欢迎下次反馈"], 200);
            }
            else{
                return response()->json(['message' => "反馈失败"], 400);
            }
        }
    }
}
