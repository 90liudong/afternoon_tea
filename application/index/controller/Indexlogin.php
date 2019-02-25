<?php
namespace app\index\controller;
use think\Controller;
class indexlogin extends controller
{
    function index(){
       getCode();
    }
    function login(){
        // echo "212121113";exit;
        $code = $_GET['code'];
        // mp($code);
        $data = getAccessToken($code);
        // mp($data);
        $user = model('user');
        $result = $user -> findman($data['openid']);
        session('openid',$data['openid']);
        if ($result) {
            session('uid',$result['id']);
        }else{
            // 获取用户信息
            $result = getUserData($data['access_token'],$data['openid']);
            $result = json_decode($result,true);

            // 获取userid
            $date = date('Ymd',time());
            $date = substr($date,2,6);
            $result1 = $user -> findlastid();
            $result1 = sprintf("%04d",$result1);
            $result['userid'] = $date.$result1;
            $result = $user -> add($result);
            $manc = model('mancoupon');
            $manc -> addone(1,$result);
            session('uid',$result);
        }
        $this ->redirect('index/pro/index');
    }
    function sess(){
        session(null);
    }
    function send_login1(){
        $code = $_GET['code'];
        $data = getAccessToken($code);
        $result = getUserData($data['access_token'],$data['openid']);
        model('sendman')->add($result); 
        exit("<script>alert('成功加入订单推送，请注意公众号信息');window.location.close();</script>");
    }
    function send_login(){
        getCode1();
    }
}