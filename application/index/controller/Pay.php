<?php

/**
 * Created by Sperk.
 * 微信支付控制器
 */
 
namespace app\index\controller;
header('Content-type:text/html;charset=utf-8');


class Pay{

    private $app_id = 'wx1f2862ac2e460e46';                                                      
    // Your appid
    private $mch_id = '1481946572';                                                      
    // Your 商户号
    private $makesign = 'CP2qIdDhga8WPLJ9dQ5tyCMIGGNnwlcB';                                                   
     // Your API支付的签名(在商户平台API安全按钮中获取)
    private $parameters=NULL;
    private $notify='http://xwc.enj1.com/index.php/index/pay/notify';                             
    //配置回调地址(给pays中转文件上传到根目录下面)
    private $app_secret='02a08ee21768e5f448d052ad0b5525db';                                                    
    //Your appSecret 微信官方获取
    public $error = 0;
    public $orderid = null;
    public $openid = "session('openid')";
    public $uid = "session('uid')";

    function fpay(){
        $order = model('orders');
        $orders = $order -> getorder1($_POST);
        $order -> changesta($orders['id'],0);
        $manc = model('mancoupon');
        $manc->changesta($orders['did'],1);
        header("location:wxpay.html?cid=".$orders['id']);
    }
    
    //进行微信支付
    public function wxPay($cid){
        $order = model('orders');
        $orders = $order -> getorder2($cid);

        $uid = session('uid');
        $no = 'A'.date("YmdHis").rand(1000, 9999);  //以后可以当做 订单号
        $pays = $orders['sendmoney']+$orders['money']-$orders['dismoney'];
        session('pays',$pays);
        $str = $uid.','.$orders['id'];
        //$pays获取需要支付的价格·
    
        $conf = $this->payconfig($no,$pays*100, '支付商品费用',$str);
        if (!$conf || $conf['return_code'] == 'FAIL') exit("<script>alert('对不起，微信支付接口调用错误!" . $conf['return_msg'] . "');history.go(-1);</script>");
        $this->orderid = $conf['prepay_id'];
        //微信相关配置如果不正的话，进入支付页面会出现错误信息

       //生成页面调用参数
        $jsApiObj["appId"] = $conf['appid'];
        $timeStamp = time();
        $jsApiObj["timeStamp"] = "$timeStamp";
        $jsApiObj["nonceStr"] = $this->createNoncestr();
        $jsApiObj["package"] = "prepay_id=" . $conf['prepay_id'];
        $jsApiObj["signType"] = "MD5";
        $jsApiObj["paySign"] = $this->MakeSign($jsApiObj);
        
        $json = json_encode($jsApiObj);
    //返回支付页面，并将相关参数返回给JS
    return view('Pay',['parameters' => $json]);
    }

    //订单管理
    #微信JS支付参数获取#
    protected function payconfig($no, $fee, $body,$str)
    {
        $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
        $data['appid'] = $this->app_id;
        $data['mch_id'] = $this->mch_id;                       //商户号
        $data['attach'] = $str;                                //数据包
        $data['device_info'] = 'WEB';
        $data['body'] = $body;
        $data['out_trade_no'] = $no;                           //订单号
        $data['total_fee'] = $fee;                             //金额
        $data['spbill_create_ip'] = $_SERVER["REMOTE_ADDR"];   //ip地址
        $data['notify_url'] = $this->notify;
        $data['trade_type'] = 'JSAPI';
        $data['openid'] = session('openid');                 //获取保存用户的openid
        $data['nonce_str'] = $this->createNoncestr();
        $data['sign'] = $this->MakeSign($data);
        
        
        $xml = $this->ToXml($data);
        $curl = curl_init(); // 启动一个CURL会话
        
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        
        //设置header
        curl_setopt($curl, CURLOPT_HEADER, FALSE);
        
        //要求结果为字符串且输出到屏幕上
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($curl, CURLOPT_POST, TRUE); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        $tmpInfo = curl_exec($curl); // 执行操作
        curl_close($curl); // 关闭CURL会话
        $arr = $this->FromXml($tmpInfo);
        return $arr;
    }

    public function notify(){  
        $xml = file_get_contents("php://input");; 
        // 这句file_put_contents是用来查看服务器返回的XML数据 测试完可以删除了  
          
        //将服务器返回的XML数据转化为数组  
        $data = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);  
        // $data = xmlToArray($xml);  
        // 保存微信服务器返回的签名sign  
        $data_sign = $data['sign'];  
        // sign不参与签名算法  
        unset($data['sign']);  
        $sign = $this->MakeSign($data);  
          
        // 判断签名是否正确  判断支付状态  
        if ( ($sign===$data_sign) && ($data['return_code']=='SUCCESS') && ($data['result_code']=='SUCCESS') ) {  
            session('pays',null);
            $fee = $data['total_fee']/100;
            $arr = explode(',', $data['attach']);
            $order = model('orders');
            $orders = $order -> getorder2($arr[1]);
            $order -> changesta($arr[1],1);
            if ($orders['did']!=0) {
                $manc = model('mancoupon');
                $result1 = $manc->changesta($orders['did'],2);
            }
            $man = model('sendman');
            $man = $man -> getsend();

            $add = model('address');
            $address = $add -> getadd($orders['aid']);
            $add = $address['name'].','.$address['tel'].','.$address['address'].','.$address['doornumber'];

            $good = '';
            foreach ($orders['good'] as $key => $value) {
                $good = $good.$orders['good'][$key]['name'].'x'.$orders['good'][$key]['num'].',';
            }

            $id = 'OPENTM401973756';
            $data=array(
                'first'=>array('value'=>urlencode("您有一笔新订单！"),'color'=>"#FF0000"),
                'keyword1'=>array('value'=>urlencode($good),'color'=>'#000000'),
                'keyword2'=>array('value'=>urlencode($orders['ordernum']),'color'=>'#000000'),
                'keyword3'=>array('value'=>urlencode($fee."元"),'color'=>'#FF0000'),
                'keyword4'=>array('value'=>urlencode("微信支付"),'color'=>'#000000'),
                'keyword5'=>array('value'=>urlencode($add),'color'=>'#000000'),
                'remark'=>array('value'=>urlencode('送达时间：'.$orders['sendtime'].'<br>备注：'.$orders['extra']),'color'=>'#000000')
            );
            foreach ($man as $key => $value) {
                $res = sendtext($man[$key]['openid'],$id,'0',$data);
            }
            file_put_contents(APP_PATH.'../public/static/text/log3.txt',1111);  
            // $data = json_encode($data);
        }else{  
            $result = false;  
            file_put_contents(APP_PATH.'../public/static/text/log20.txt',$result);  
        }  
        // 返回状态给微信服务器  
        // if ($result) {  
        //     $str='SUCCESS';  
        // }else{ 
        //     $str='SUCCESS';  
        // }  
        // echo $str;  
        echo 'FALSE';  
        exit;
    }

    /**
     *    作用：产生随机字符串，不长于32位
     */
    public function createNoncestr($length = 32){
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     *    作用：产生随机字符串，不长于32位
     */
    public function randomkeys($length)
    {
        $pattern = '1234567890123456789012345678905678901234';
        $key = null;
        for ($i = 0; $i < $length; $i++) {
            $key .= $pattern{mt_rand(0, 30)};    //生成php随机数
        }
        return $key;
    }

    /**
     * 将xml转为array
     * @param string $xml
     * @throws WxPayException
     */
    public function FromXml($xml)
    {
        //将XML转为array
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }

    /**
     * 输出xml字符
     * @throws WxPayException
     **/
    public function ToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    protected function MakeSign($arr)
    {
        //签名步骤一：按字典序排序参数
        ksort($arr);
        $string = $this->ToUrlParams($arr);
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=" . $this->makesign;
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams($arr)
    {
        $buff = "";
        foreach ($arr as $k => $v) {
            if ($k != "sign" && $v != "" && !is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        $buff = trim($buff, "&");
        return $buff;
    }
     /**
     * @return 用户的openid
     */
    public function GetOpenid()
    {
        //通过code获得openid
        if (!isset($_GET['code'])){
            //触发微信返回code码
            $baseUrl = urlencode('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
            $url = $this->__CreateOauthUrlForCode($baseUrl);
            Header("Location: $url");
            exit();
        } else {
            //获取code码，以获取openid
            $code = $_GET['code'];
            $openid = $this->getOpenidFromMp($code);
            return $openid;
        }
    }
    /**
     * 
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * 
     * @return 返回构造好的url
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {
        $urlObj["appid"] = $this->app_id;
        $urlObj["redirect_uri"] = "$redirectUrl";
        $urlObj["response_type"] = "code";
        $urlObj["scope"] = "snsapi_base";
        $urlObj["state"] = "STATE"."#wechat_redirect";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://open.weixin.qq.com/connect/oauth2/authorize?".$bizString;
    }
    /**
     * 
     * 构造获取open和access_toke的url地址
     * @param string $code，微信跳转带回的code
     * 
     * @return 请求的url
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $urlObj["appid"] = 'wx7ab9d902bb0ba2c0';
        $urlObj["secret"] = 'edf6e9063dc033e9200197546057983a';
        $urlObj["code"] = $code;
        $urlObj["grant_type"] = "authorization_code";
        $bizString = $this->ToUrlParams($urlObj);
        return "https://api.weixin.qq.com/sns/oauth2/access_token?".$bizString;
    }
    /**
     * 
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * 
     * @return openid
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
        //初始化curl
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, 120);
        //curl_setopt($ch, CURLOPT_TIMEOUT, $this->curl_timeout);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if(WxPayConfig::CURL_PROXY_HOST != "0.0.0.0" 
            && WxPayConfig::CURL_PROXY_PORT != 0){
            curl_setopt($ch,CURLOPT_PROXY, WxPayConfig::CURL_PROXY_HOST);
            curl_setopt($ch,CURLOPT_PROXYPORT, WxPayConfig::CURL_PROXY_PORT);
        }
        //运行curl，结果以jason形式返回
        $res = curl_exec($ch);
        curl_close($ch);
        //取出openid
        $data = json_decode($res,true);
        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }
}