<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlanController extends Controller
{
    public function show($id){
        $a = DB::table("plan")->where(["user_id" => $id, "create_time_data" => date("Y-m-d")])->get();
        return $a;
    }
    public function store(Request $request){
        $text = $request->get("content");
        if($text == ""){
            return response()->json(["message" => "计划不能为空", "status" => 400]);
        }
        if(mb_strlen($text) > 10){
            return response()->json(["message" => "计划太长", "status" => 400]);
        }
        $user_id = $request->get("userid");
        $count = DB::table("plan")->where(["user_id" => $user_id, "create_time_data" => date("Y-m-d")])->get();        
        if(count($count) == 5){
            return response()->json(["message" => "计划过多", "status" => 400]);
        }
        $id = time() . md5(uniqid());
        $create_time = date("Y-m-d H:i:s");
        $create_time_data = date("Y-m-d");
        $a = DB::table("plan")->insert([
            "id" => $id,
            "user_id" => $user_id,
            "text" => $text,
            "create_time" => $create_time,
            "create_time_data" => $create_time_data,
            "encourage" => $request->get("encourage"),
            "start_time" => $request->get("start_time")
        ]);
        if($a){
            return response()->json(["message" => "提交成功", "status" => 200]);
        }
    }
}
