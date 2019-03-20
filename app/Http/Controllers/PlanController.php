<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlanController extends Controller
{
    public function show($id){
        $a = DB::table("plan")->where("user_id", $id)->get();
        return $a;
    }
    public function store(Request $request){
        $text = $request->get("content");
        if($text == ""){
            return response()->json(["message" => "计划不能为空！", "status" => 400]);
        }
        $user_id = $request->get("userid");
        $count = DB::table("plan")->where(["user_id" => $user_id, "create_time_data" => date("Y-m-d")])->get();        
        if(count($count) == 5){
            return response()->json(["message" => "每天只能计划\r\n5件事！", "status" => 400]);
        }
        $bg = 'bg' . rand(1, 4);
        $id = time() . md5(uniqid());
        $create_time = date("Y-m-d H:i:s");
        $create_time_data = date("Y-m-d");
        $a = DB::table("plan")->insert([
            "id" => $id,
            "user_id" => $user_id,
            "text" => $text,
            "create_time" => $create_time,
            "bg" => $bg,
            "create_time_data" => $create_time_data
        ]);
        if($a){
            return response()->json(["message" => "提交成功", "status" => 200]);
        }
    }
}
