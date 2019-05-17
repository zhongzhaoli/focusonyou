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
            'contact' => 'required',
            'phone' => 'required',
            'cover' => "required",
            'menu' => "required",
            'af_start_time' => 'required',
            'af_end_time' => 'required'
        ],[
            'name.required' => "商家名不能为空",
            'start_time.required' => "上午外卖开始时间不能为空",
            'end_time.required' => "上午外卖结束时间不能为空",
            'af_start_time.required' => "下午外卖开始时间不能为空",
            'af_end_time.required' => "下午外卖结束时间不能为空",
            'contact.required' => "外卖联系方式类型不能为空",
            'phone.required' => "外卖联系方式不能为空",
            'cover.required' => "封面图不能为空",
            'menu.required' => "菜单图片不能为空",
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);            
        }
        $cover = [];
        $menu = [];
        for($i = 0; $i < count($img_arr); $i++){
            for($j = 0; $j < count($img_arr[$i]); $j++){
                $prove_up = new ProveUpload();
                $bo_prove = $prove_up->upload($img_arr[$i][$j],"uploads/takeout/");
                if(!$bo_prove){
                    return response()->json(["prove" => "图片有错"],400);
                }
                // $prove_url = "http://localhost:7889/".$bo_prove;
                $prove_url = "https://api.yuntunwj.com/focusonyou/public/".$bo_prove;
                ($i == 0) ? array_push($cover, $prove_url) : array_push($menu, $prove_url);
                $request["prove"] = $prove_url;
            }
        }

        $a = DB::table("takeout")->insert([
            "id" => time() . md5(uniqid()),
            "name" => $request->get("name"),
            "phone" => $request->get("contact") . "：" .$request->get("phone"),
            "start_time" => $request->get("start_time"),
            "end_time" => $request->get("end_time"),
            "af_start_time" => $request->get("af_start_time"),
            "af_end_time" => $request->get("af_end_time"),
            "create_time" => date("Y-m-d H:i:s"),
            "cover" => json_encode($cover),
            "menu" => json_encode($menu),
            "operator" => $request->user()->name
        ]);
        if($a){
            return response()->json(["message" => ["提交成功"]],200);
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
