<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class PlanController extends Controller
{
    public function show($id){
        $a = DB::table("plan")->where("user_id", $id)->get();
        if(count($a)){
            return $a;
        }
        else{
            return response()->json(["message" => "null"], 400);
        }
    }
}
