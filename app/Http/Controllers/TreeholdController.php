<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TreeholdController extends Controller
{
    public function all_tree(){
        $a = DB::table("treehold")->orderBy("create_time", 'desc')->get();
        return $a;
    }
    public function show($id){
        $a = DB::table("treehold_mes")->where("tree", $id)->get();
        $b = DB::table("treehold")->where("id", $id)->get();
        return response()->json(["sj" => $a, "title" => $b], 200);
    }
    public function send($id, Request $request){
        if(!$request->get("content")){
            return response()->json(["message" => "内容不能为空噢"], 400);
        }
        else{
            $b = DB::table("treehold")->where("id", $id)->get();
            if(!count($b)){
                return response()->json(["message" => "管理员还没创建此树洞哦"], 400);
            }
            $a = DB::table("treehold_mes")->insert([
                "id" => time() . md5(uniqid()),
                "content" => $request->get("content"),
                "create_time" => date("Y-m-d H:i:s"),
                "tree" => $id
            ]);
            if($a){
                return response()->json([],200);
            }
            else{
                return response()->json(["message" => "发送失败"], 400);
            }
        }
    }
    public function store(Request $request){
        if(!$request->get("title")){
            return response()->json(["message" => "推文标题不能为空哦"], 400);
        }
        else{
            $b = DB::table("treehold")->where("title", $request->get("title"))->get();
            if(count($b)){
                return response()->json(["message" => "树洞已存在"], 400);
            }
            $id = time() . md5(uniqid());
            $create_time = date("Y-m-d H:i:s");
            $a = DB::table("treehold")->insert([
                "id" => $id,
                "create_time" => $create_time,
                "title" => $request->get("title"),
                "operator" => $request->user()->name
            ]);
            if($a){
                return response()->json(["id" => $id], 200);
            }
            else{
                return response()->json(["message" => "获取失败"], 400);
            }
        }
    }
}
