<?php
namespace app\index\controller;
use think\Controller;

class Pro extends Indexbase{
	// Indexbase
	function index(){
		$tip = model('Tip')->gettip();
		$this->assign("tip",$tip);
		// mp($tip);
		$goods = model('Tip')->getgoods();
		$this->assign("goods",$goods);

		$godid = $goods[0]['id'];
		$this->assign("godid",$godid);

		$goodss = model('Goods')->getgoodss();
		$this->assign("goodss",$goodss);
		// require_once APP_PATH."../utils/php/jssdk.php";
  //       $jssdk = new \JSSDK("wx1f2862ac2e460e46", "02a08ee21768e5f448d052ad0b5525db");
  //       $signPackage = $jssdk->GetSignPackage();
        // $this->assign('signPackage',$signPackage);
		return $this->fetch();
	}
	function addorder(){
		$order = model('orders');
		$uid = session('uid');
		$add = model('address');
		$address = $add -> gettype();
		if (empty($address)) {
			$aid = 0;
		}else{
			$aid = $address['id'];
		}
		$result = $order -> addorder($_POST,$aid);
		return $result;
	}
}