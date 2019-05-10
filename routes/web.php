<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: *");
header("Access-Control-Allow-Headers: Content-Type,Access-Token,Authorization");
header("Access-Control-Expose-Headers: *");
// ----------------------------------普通用户权限---------------------------------------
//上诉API
Route::post("appeal", "AppealController@store");
//计划API
Route::resource("plan", "PlanController");
//微信
Route::post("/wechat", "WechatController@index");
//早睡打卡
Route::resource("sleep", "SleepController");
//登陆注册
Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');
//获取所有商家
Route::get("/takeout", "TakeoutController@index");
Route::get("/takeout/{id}", "TakeoutController@show");
//获取树洞
Route::get("/treehold/{id}", "TreeholdController@show");
//发送树洞
Route::post("/tree_send/{id}", "TreeholdController@send");

// ----------------------------------母亲节活动----------------------------------
Route::group(['prefix' => 'mother'], function(){
    //来自星星的短信
    Route::post("/message", "MotherController@store");
             Route::post("/poster", "MotherController@poster_num");
});


// ----------------------------------管理员用户权限-------------------------------
Route::group(['middleware' => 'auth:api'], function(){
    //返回管理员姓名
    Route::get("/isadmin", "PassportController@isadmin");
    //查看反馈和上诉
    Route::get("appeal/{table_name}", "AppealController@show");
    //外卖商家发布
    Route::post("/takeout", "TakeoutController@store");
    //生成树洞
    Route::post("/treehold", "TreeholdController@store");
    //获取所有树洞
    Route::get("/tree_all", "TreeholdController@all_tree");
});
