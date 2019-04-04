<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use DB;

class TakeoutController extends Controller
{
    public function index(){
        $a = DB::table("takeout")->get();
        return $a;
    }
    public function store(Request $request){
        $img_arr = [$request->get("cover"), $request->get("menu")];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'phone' => 'required|regex:/^1[345789][0-9]{9}$/',
            'cover' => "required",
            'menu' => "required"
        ],[
            'name.required' => "商家名不能为空",
            'start_time.required' => "外卖开始时间不能为空",
            'end_time.required' => "外卖结束时间不能为空",
            'phone.required' => "外卖电话不能为空",
            'phone.regex' => "外卖电话不合法",
            'cover.required' => "封面图不能为空",
            'menu.required' => "菜单图片不能为空",
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);            
        }
        $cover = "";
        $menu = "";
        for($i = 0; $i < count($img_arr); $i++){
            $prove_up = new ProveUpload();
            $bo_prove = $prove_up->upload($img_arr[$i],"uploads/");
            if(!$bo_prove){
                return response()->json(["prove" => "图片有错"],400);
            }
            // $prove_url = "http://localhost:7889/".$bo_prove;
            $prove_url = "https://api.yuntunwj.com/focusonyou/public/".$bo_prove;
            ($i == 0) ? $cover = $prove_url : $menu = $prove_url;
            $request["prove"] = $prove_url;
        }

        $a = DB::table("takeout")->insert([
            "id" => time() . md5(uniqid()),
            "name" => $request->get("name"),
            "phone" => $request->get("phone"),
            "start_time" => $request->get("start_time"),
            "end_time" => $request->get("end_time"),
            "create_time" => date("Y-m-d H:i:s"),
            "cover" => $cover,
            "menu" => $menu
        ]);
        if($a){
            return response()->json(["message" => ["提交成功"]],200);
        }
        else{
            return response()->json(["message" => ["提交失败"]],400);
        }
    }
    public function show($id){
        $a = DB::table("takeout")->where("id", $id)->get();
        if(!count($a)){
            return response()->json(["message" => "没有找到此商家"], 400);
        }
        return $a;
    }
}
