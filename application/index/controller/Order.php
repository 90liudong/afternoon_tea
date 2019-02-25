<?php
namespace app\index\controller;
use think\Controller;

class Order extends Controller{

	function index(){
		$order = model('orders');
		$orders = $order -> getorder4();
		$this->assign('order',$orders);
		return $this->fetch();
	}
	function autotime(){
		$order = model('orders');
		$coupon = model('mancoupon');
		$ordergoods = model('ordergoods');
		$goods = model('goods');
		$result = $order -> getnotpay();
		foreach ($result as $key => $value) {
			if (($result[$key]['ordertime']+1800)<time()) {
				$order ->changesta($result[$key]['id'],5);
				if ($result[$key]['did']!=0) {
					$coupon ->changesta($result[$key]['did'],0);
				}
				$ordergoods = $ordergoods -> getordergoods($result[$key]['id']);
				foreach ($ordergoods as $key => $value) {
					$goods -> updatenum($ordergoods[$key]['gid'],$ordergoods[$key]['num']);
				}
			}
		}
	}
}