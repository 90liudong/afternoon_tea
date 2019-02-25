<?php
namespace app\index\controller;
use think\Controller;

class Bargin extends Controller{

	function index(){
		$uid = session('uid');
		$mancoupon = model('mancoupon');
		$data = $mancoupon -> getman1($uid);	

		$order = model('orders');
		$orders = $order -> getorder();
		if ($orders['ordertime']+1800<time()) {
			$order -> deleteorder();
	    	exit("<script>alert('对不起,订单处于未支付状态已经三十分钟，请重新下单。');history.go(-1);</script>");
		}
		
		foreach ($data as $key => $value) {	
			$data[$key]['reason'] = [];
			$data[$key]['bargin_money'] = 0;
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
								$data[$key]['reason'][] = $orders['good'][$ke]['name'].'不在优惠券的使用范围';
							}
						}
					}
					switch ($data[$key]['type']) {
						case '0':
							$data[$key]['bargin_money'] = sprintf("%.2f",$orders['money']/2);
							break;
						case '1':
							if ($orders['num']<2) {
								$data[$key]['reason'][] = '订单份数需大于等于2杯才能使用该优惠券。';
							}else{
								if (empty($data[$key]['reason'])) {
									$data[$key]['bargin_money'] = $orders['good'][0]['money'];
								}
							}
							break;
						case '2':
							if ($orders['money']<$data[$key]['condition']) {
								$data[$key]['reason'][] = '订单总额未达到'.$data[$key]['condition'].'元。';
							}else{
								if (empty($data[$key]['reason'])) {
									switch ($data[$key]['moneytype']) {
										case '4':
											$data[$key]['bargin_money'] = 5;
											break;
										case '1':
											$data[$key]['bargin_money'] = $orders['money'] - $data[$key]['money'];
											break;
										case '2':
											$data[$key]['bargin_money'] = $orders['money']*$data[$key]['money']*0.1;
											$data[$key]['bargin_money'] = sprintf("%.2f",$data[$key]['bargin_money']);
											break;
										case '3':
											$data[$key]['bargin_money'] = 0;
											for ($i=0; $i < $data[$key]['money']; $i++) { 
												$data[$key]['bargin_money'] = $orders['good'][$i]['money'] + $data[$key]['bargin_money'];
											}
											break;
										default:
											break;
									}
								}else{
								}
							}
							break;
						case '3':
							if ($orders['num']<$data[$key]['condition']) {
								$data[$key]['reason'][] = '订单份数未达到'.$data[$key]['condition'].'杯。';
							}else{
								if (empty($data[$key]['reason'])) {
									switch ($data[$key]['moneytype']) {
										case '4':
											$data[$key]['bargin_money'] = 5;
											break;
										case '1':
											$data[$key]['bargin_money'] = $orders['money'] - $data[$key]['money'];
											break;
										case '2':
											$data[$key]['bargin_money'] = $orders['money']*$data[$key]['money']*0.1;
											$data[$key]['bargin_money'] = sprintf("%.2f",$data[$key]['bargin_money']);
											break;
										case '3':
											$data[$key]['bargin_money'] = 0;
											for ($i=0; $i < $data[$key]['money']; $i++) { 
												$data[$key]['bargin_money'] = $orders['good'][$i]['money'] + $data[$key]['bargin_money'];
											}
											break;
										default:
											break;
									}
								}else{
								}
							}
							break;
						default:
							break;
					}
					break;
					break;
				case '1':
					$data[$key]['reason'][] = '优惠券已经被锁定，该订单未支付。';
					break;
				case '2':
					$data[$key]['reason'][] = '优惠券已过期。';
					break;
				default:
					$data[$key]['reason'][] = '优惠券已使用';
					break;
			}
		}
		$arr = array();
		$arr1 = array();
		foreach ($data as $key => $value) {
			if ($data[$key]['bargin_money'] == 0) {
				$arr[] = $data[$key];
			}else{
				$arr1[] = $data[$key];
			}
		}

		$flag=array();
		foreach($arr1 as $arr2){
		    $flag[]=$arr2["bargin_money"];
		}
		array_multisort($flag, SORT_DESC, $arr1);

		$this->assign('did',$orders['did']);
		$this->assign('arr1',$arr1);
		$this->assign('arr',$arr);
		return $this->fetch();
	}
	function addcou($cid,$money){
		$order = model('orders');
		$order -> changecou($cid,$money);
	}
	function yhq($id=0){
		if ($id==0) {
			$this -> redirect('index/pro/index');
		}
		$uid = session('uid');
		$coupon = model('coupon');
		$mancoupon = model('Mancoupon');
		// 检查优惠券是否过期
		updatecou($id);
		$cou = $coupon -> getone($id);
		if (!$cou) {
			$this->error('该优惠券不存在','index/pro/index',1);
		}
		if (request()->isPost()) {
			$cou = $coupon -> getone($id);
			if ($cou['number']==0) {
				return '已经领取完了';
			}
			if ($cou['status']==1) {
				return '优惠券已经过了有效使用期，欢迎下次再参加活动。';
			}
			if ($cou['limi']!=0) {
				$limi = $mancoupon -> getlimi($id,$uid);
				$limi = count($limi);
				if ($limi>=$cou['limi']) {
					return '每人只能领取'.$cou['limi'].'张,留点给别人吧。';
				}
			}
			$mancoupon -> addone($id,$uid);
			$coupon -> downnumber($id,$cou['number']-1);
			return '领取成功，请快去使用吧。';
		}
		if ($cou['status']==1) {
			$cou['status'] = 'true';
		}else{
			$cou['status'] = 'false';
		}
		if ($cou['number']==0) {
			$cou['number'] = 'false';
		}else{
			$cou['number'] = 'true';
		}
		$this->assign('cou',$cou);
		return $this->fetch();
	}
	
}