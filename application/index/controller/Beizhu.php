<?php
namespace app\index\controller;
use think\Controller;

class Beizhu extends Controller{

	function index(){
		$order = model('orders');
		$orders = $order -> getextra();
		$this->assign('order',$orders);
		return $this->fetch();
	}
	function addbeizhu(){
		session('uid',4);
		$str = $_GET['str'];
		$order = model('orders');
		$orders = $order -> upextra($str);
		return $orders;
	}
}