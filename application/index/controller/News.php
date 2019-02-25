<?php
namespace app\index\controller;
use think\Controller;

class News extends Controller{

	function waite($cid){
		$orders = model('orders');
		$order = $orders -> getorder3($cid);
		$this -> assign('list',$order);
		return $this->fetch();
	}
	function make($cid){
		session('uid',4);
		$orders = model('orders');
		$order = $orders -> getorder3($cid);
		$this -> assign('list',$order);
		return $this->fetch();
	}
	function song($cid){
		session('uid',4);
		$orders = model('orders');
		$order = $orders -> getorder3($cid);
		$this -> assign('list',$order);
		return $this->fetch();
	}
	function arrive($cid){
		session('uid',4);
		$orders = model('orders');
		$order = $orders -> getorder3($cid);
		$this -> assign('list',$order);
		return $this->fetch();
		
	}
	function cancel($cid){
		$orders = model('orders');
		$order = $orders -> getorder3($cid);
		$this -> assign('list',$order);
		return $this->fetch();
	}
	function updatenew($static){
		$orders = model('orders');
		$id = $orders -> getoid($static);
		if (empty($id)) {
			$id['id'] = 0;
		}
		return json($id);
	}
	
}