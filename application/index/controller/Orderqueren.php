<?php
namespace app\index\controller;
use think\Controller;

class Orderqueren extends Controller{

	function index(){
		$uid = session('uid');
		$add = model('address');
		$address = $add -> getuid($uid);
		if (empty($address)) {
			$type = 0;
		}else{
			$type = 1;
		}
		$this->assign('add',$address);

		$order = model('orders');
		$orders = $order -> getorder();
		if (empty($orders['good'])) {
			$this -> redirect('index/pro/index');
		}
		$str = "index/pro/index";
		if ($orders['ordertime']+1800<time()) {
			$order -> deleteorder();
	    	exit("<script>alert('对不起,订单处于未支付状态已经三十分钟，请重新下单。');window.location.href='./../index/pro/index.html';</script>");
		}
		
		
		$coupon = model('mancoupon');
		$data = $coupon -> getman1($uid);
		foreach ($data as $key => $value) {	
			$data[$key]['reason'] = 0;
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
			switch ($data[$key]['status']) {
				case '0':
					if ($data[$key]['tiptype']!=0) {
						foreach ($orders['good'] as $ke => $value) {
							$flag = '1';
							foreach ($data[$key]['tip'] as $ky => $value) {
								if ($orders['good'][$ke]['name']==$data[$key]['tip'][$ky]) {
									$flag = '2';
								}
							}
							if ($flag=='1') {
								$data[$key]['reason'] = 1;
							}
						}
					}
					switch ($data[$key]['type']) {
						case '1':
							if ($orders['num']<2) {
								$data[$key]['reason'] = 1;
							}
							break;
						case '2':
							if ($orders['money']<$data[$key]['condition']) {
								$data[$key]['reason'] = 1;
							}
							break;
						case '3':
							if ($orders['num']<$data[$key]['condition']) {
								$data[$key]['reason'] = 1;
							}
							break;
						default:
							break;
					}
					break;
				case '1':
					$data[$key]['reason'] = 1;
					break;
				case '2':
					$data[$key]['reason'] = 1;
					break;
				default:
					$data[$key]['reason'] = 1;
					break;
			}
		}
		$j = 0;
		for ($i=0; $i < count($data) ; $i++) { 
			if ($data[$i]['reason']!=1) {
				$j++;
			}
		}
		$this->assign('j',$j);

		if (session('?bargin_money')) {
			$bargin_money = session('bargin_money');
		}else{
			$bargin_money = 0;
		}
		if ($orders['extra']=='0') {
			$orders['extra'] = '口味和其他特殊要求在这里说明';
		}else{
			if (mb_strlen($orders['extra'])>14) {
				$orders['extra'] = mb_substr($orders['extra'],0,14).'...';
			}
		}
		$mancou = model('mancoupon');
		$coupon = $mancou -> getone($orders['did']);
		$this->assign('bargin_money',$bargin_money);
		$this->assign('cou',$coupon);
		$this->assign('order',$orders);
		$this->assign('time',time());
		$this->assign('type',$type);
		return $this->fetch();
	}
	function indexx(){
		
		return $this->fetch();
	}
	
}