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
            return response()->json(["message" => "计划不能为空哦！"], 400);
        }
        $user_id = $request->get("userid");
        $bg = 'bg' . rand(1, 4);
        $id = time() . md5(uniqid());
        $create_time = date("Y-m-d H:i:s");
        $a = DB::table("plan")->insert([
            "id" => $id,
            "user_id" => $user_id,
            "text" => $text,
            "create_time" => $create_time,
            "bg" => $bg
        ]);
        if($a){
            return response()->json(["message" => "提交成功"], 200);
        }
    }
}
