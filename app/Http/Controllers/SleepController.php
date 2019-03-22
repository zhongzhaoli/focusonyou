<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SleepController extends Controller
{
    public function store(Request $request){
        $now_hour = date("H");
        if($now_hour > 20 && $now_hour < 23){
            $user_id = $request->get("user_id");            
            $create_time = date("Y-m-d H:i:s");
            $create_time_data = date("Y-m-d");
            $clock_time = date("H:i:s");
            $a = DB::table("sleep")->insert([
                "id" => time() . md5(uniqid()),
                "user_id" => $user_id,
                "create_time" => $create_time,
                "create_time_data" => $create_time_data,
                "clock_time" => $clock_time
            ]);
            if($a){
                return response()->json(["message" => "打卡成功", "status" => "200"], 200);                
            }
            else{
                return response()->json(["message" => "打卡失败", "status" => "400"], 200);                
            }
        }
        else{
            if($now_hour < 20){
                return response()->json(["message" => "你来早了哦", "status" => "400"], 200);
            }
            if($now_hour > 23){
                return response()->json(["message" => "你来晚了哦", "status" => "400"], 200);                
            }
        }
    }
}
