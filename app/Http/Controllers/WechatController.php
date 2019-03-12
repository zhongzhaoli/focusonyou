<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;

use Illuminate\Http\Request;

class WechatController extends Controller
{
    public function index(Request $request){
        $client = new Client();
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $request->get("appid") . '&secret=' . $request->get("secret") . '&js_code=' . $request->get('code') . '&grant_type=authorization_code';
        $res = $client->request('GET', $url);
        // $res->getStatusCode();
        // // "200"
        // $res->getHeader('content-type');
        // // 'application/json; charset=utf8'
        return $res->getBody();
    }
}
