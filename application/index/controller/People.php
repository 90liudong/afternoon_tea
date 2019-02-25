<?php
namespace app\index\controller;
use think\Controller;

class People extends Controller{

	function index(){
		$uid = session('uid');
		$user = model('user');
		$user = $user ->getone($uid);
		$this ->assign('user',$user);
		return $this->fetch();
	}
	function coupon(){
		$uid = session('uid');
		$mancoupon = model('mancoupon');
		$data = $mancoupon -> getman($uid);
		foreach ($data as $key => $value) {
			if ($data[$key]['timetype']==2) {
				$cou = strtotime($data[$key]['close']);
		        if ($cou<time()) {
		            $mancoupon -> changesta($data[$key]['cid'],2);
		            $data[$key]['status'] = 1;
		        }
			}else{
				$cou = $data[$key]['time']+($data[$key]['close']-$data[$key]['open'])*86400;
				if ($cou<time()) {
		            $mancoupon -> changesta($data[$key]['id'],2);
		            $data[$key]['status'] = 1;
				}
			}
		}
		$this->assign('data',$data);
		return $this->fetch();
	}
}