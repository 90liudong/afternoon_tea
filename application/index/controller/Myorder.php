<?php
namespace app\index\controller;
use think\Controller;

class Myorder extends Controller{

	function index(){
		$order = model('orders');
		$ordergoods = model('ordergoods');
		$orders = $order -> getmanorder($uid);
		foreach ($orders as $key => $value) {
			$orders[$key]['ordertime'] = date("Y-m-d H:m:s",$orders[$key]['ordertime']);
			$orders[$key]['good'] = $ordergoods -> getfirstgood($orders[$key]['id']);
		}
		$this -> assign('data',$orders);
		return $this->fetch();
	}
	function dele($id,$status){
		$order = model('orders');
		$ordergood = model('ordergoods');
		$result = $order -> getone($id);
		if ($status==0) {
			// 取消
			$coupon = model('mancoupon');
			$goods = model('goods');
			$order ->changesta($result['id'],5);	
			if ($result['did']!=0) {
				$coupon ->changesta($result['did'],0);
			}
			$ordergoods = $ordergood -> getordergoods($result['id']);
			foreach ($ordergoods as $key => $value) {
				$goods -> updatenum($ordergoods[$key]['gid'],$ordergoods[$key]['num']);
			}
		}else{
			// 删除
			$order -> deleid($id);
			$ordergood -> deleoid($id);
		}
	}
	
}