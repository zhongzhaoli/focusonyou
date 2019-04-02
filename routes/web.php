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

//上诉API
Route::resource("appeal", "AppealController");
//计划API
Route::resource("plan", "PlanController");
//微信
Route::post("/wechat", "WechatController@index");
//早睡打卡
Route::resource("sleep", "SleepController");

Route::post('login', 'PassportController@login');
Route::post('register', 'PassportController@register');


Route::group(['middleware' => 'auth:api'], function(){
	Route::get('/admin', function(){
        return "dasdas";
    });
});