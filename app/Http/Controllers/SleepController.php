<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class SleepController extends Controller
{
    public function show($user_id){
        //返回 今天要不要打卡，连续打卡，累计打卡，最长连续打卡
        $return_data = [
            //0是不用打卡 1是用打卡
            "need_clock" => 1,
            "clock_count" => 0,
            "continuity_clock" => 0
        ];
        //如果中途断了就不用往下计算了（连续打卡）
        $continuity_type = 1;

        //获取user的所有打卡纪录  [1,2,3,4] count = 4
        $a = DB::table("sleep")->where("user_id", $user_id)->OrderBy("create_time_date","asc")->get();
        //今天
        $today = date("Y-m-d");
        if(count($a)){
            if($a[count($a) - 1]->create_time_date == date("Y-m-d")){
                $return_data['need_clock'] = 0;
            }
            else{
                $return_data['need_clock'] = 1;
            }
            $return_data['clock_count'] = count($a);

            //连续打卡 先获取今天和数据库的第一条是否相差1 如果不是 就默认是0了
            $sql_first = (date('Y-m-d',strtotime(date('Y-m-d')) - 3600 * 24) == $a[count($a) - 1]->create_time_date || $a[count($a) - 1]->create_time_date == date("Y-m-d")) ? 'true' : 'false';
            for($i = count($a); $i > 0; $i--){
                $j = $i - 1;
                //如果就相差一天 也就是说昨天也打卡了
                if($sql_first && $i > 1 && $continuity_type){
                    //判断此次和下次是否相差一天 是的话就+1
                    if(date("Y-m-d",strtotime($a[$j]->create_time_date) - 3600 * 24) == date("Y-m-d",strtotime($a[$j - 1]->create_time_date))){
                        if($i == count($a)){
                            $return_data['continuity_clock'] = $return_data['continuity_clock'] + 2;
                        }
                        else{
                            $return_data['continuity_clock']++;
                        }
                    }
                    else{
                        //如果最后一天数据是今天的话 而且上一天不是 今天的上一天 则是1
                        if($a[$j]->create_time_date == date("Y-m-d")){
                            $return_data['continuity_clock'] = 1;
                        }
                        $continuity_type = 0;
                    }
                }
            }
        }
        return $return_data;
    }
    public function store(Request $request){
        $now_hour = date("H");
        if($now_hour > 10 && $now_hour < 23){
            $user_id = $request->get("user_id");            
            $b = DB::table("sleep")->where(["user_id" => $user_id, "create_time_date" => date("Y-m-d")])->get();
            if(!count($b)){
                $create_time = date("Y-m-d H:i:s");
                $create_time_date = date("Y-m-d");
                $clock_time = date("H:i:s");
                $a = DB::table("sleep")->insert([
                    "id" => time() . md5(uniqid()),
                    "user_id" => $user_id,
                    "create_time" => $create_time,
                    "create_time_date" => $create_time_date,
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
                return response()->json(["message" => "今天已打卡", "status" => "400"], 200);                                
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
