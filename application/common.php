<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function mp($arr){
    header("Content-type:text/html;charset=utf-8");
    echo "<pre>";
    print_r($arr);
    exit;
}
// 检查优惠券是否过期
function updatecou($id){
    $coupon = model('coupon');
    $cou = $coupon -> getone($id);
    if ($cou['timetype']!=1&&$cou['type']>1) {
        $cou = strtotime($cou['close']);
        if ($cou<time()) {
            $coupon -> changesta($id,1);
            return 1;
        }else{
            return 0;
        }
    }
}

// 分页
function getPage($maxPage,$page){
    if ($page >$maxPage) {
        $page = $maxPage;
    }
    if($page< 1) {
        $page = 1;
    }
    return $page;
}
function getPages($maxPage,$page){
    if ($page >$maxPage) {
        $page = $maxPage;
    }
    if($page< 1) {
        $page = 1;
    }
    if ($maxPage >= 4) {
        $showPage  = 4;
    }else{
        $showPage = $maxPage;
    }
    $pages=[];
    if ($showPage<4) {
        for ($i=1; $i <= $showPage ; $i++) { 
             $pages[] = $i;
        }
    }else{
        if ($page == 1 || $page == 2) {
            for ($i=1; $i <= $showPage ; $i++) { 
                 $pages[] = $i;
            }
        }
        else{
            $pp = $page;
            if ($pp+2>$maxPage) {
                $pages = [$maxPage-3,$maxPage-2,$maxPage-1,$maxPage];
            }else{
                $pages = [$page-1,$page,$page+1,$page+2];
            }
            
        }
    }
    return $pages;
}
function getCode(){
    $redirect_uri = 'http://xwc.enj1.com/index.php/index/indexlogin/login';
    // echo 88888;exit;
    $redirect_uri = urlencode($redirect_uri);
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f2862ac2e460e46&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
    header("location:".$url);
    exit;
}
function getCode1(){
    $redirect_uri = 'http://xwc.enj1.com/index.php/index/indexlogin/send_login1';
    $redirect_uri = urlencode($redirect_uri);
    $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f2862ac2e460e46&redirect_uri='.$redirect_uri.'&response_type=code&scope=snsapi_userinfo&state=123#wechat_redirect';
    header("location:".$url);
    exit;
}
function getAccessToken($code){
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1f2862ac2e460e46&secret=02a08ee21768e5f448d052ad0b5525db&code='.$code.'&grant_type=authorization_code';
    $data = file_get_contents($url);
    $data = json_decode($data,true);
    return $data; 
}
function getUserData($access_token,$openid){
    $url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';
    $data = file_get_contents($url);
   
    return $data;
}
function http_request($url,$data=array()){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    // POST数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // 把post的变量加上
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}
function sendtext($openid,$id,$url,$data){
    $json_token=http_request("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1f2862ac2e460e46&secret=02a08ee21768e5f448d052ad0b5525db");
    return $json_token;
    $access_token=json_decode($json_token,true);
    //获得access_token
    $access_token=$access_token['access_token'];
    //echo $this->access_token;exit;
    //模板消息
    if ($url=='0') {
        $template=array(
            'touser'=>"$openid",
            'template_id'=>"$id",
            'topcolor'=>"#7B68EE",
            'data'=>$data
        );
    }else{
         $template=array(
            'touser'=>"$openid",
            'template_id'=>"$id",
            'url'=>"$url",
            'topcolor'=>"#7B68EE",
            'data'=>$data
        );
    }
    $json_template=json_encode($template);
    //echo $json_template;
    //echo $this->access_token;
    $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
    $res=http_request($url,urldecode($json_template));
    // mp($res);
    // exit;
}