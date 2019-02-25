<?php
namespace app\admin\controller;
header('Content-type:text/html;charset=utf-8');
use think\Controller;

Class Index extends Adminbase{
	// 后台首页
	function index(){
			return $this->fetch();
	}
	// 活动
	function activity(){
			return $this->fetch();
	}
	// 优惠券
	function coupon($each=10,$page=1){
		$coupon = model('coupon');
		// 分页
		$size = db('coupon')->count();
        $maxPage = ceil($size/$each);
        $page = getPage($maxPage,$page);
        $pages = getPages($maxPage,$page);
        $list = $coupon -> getpage($each,$page);

		$this ->assign('data',$list);
		$this -> assign('href','coupon');
        $this -> assign("each",$each);
        $this -> assign("size",$size);
        $this -> assign("maxPage",$maxPage);
        $this -> assign("pages",$pages);
        $this -> assign("page",$page);
		return $this->fetch();
	}
	// 新增优惠券
	function newadd(){
		$tip = model('tip');
		$goodstip = model('cougood');
		if (request()->isPost()) {
			$coupon = model('coupon');
			switch ($_POST['moneytype']) {
				case '1':
					$_POST['money'] = $_POST['listmoney'];
					break;
				case '2':
					$_POST['money'] = $_POST['zhe'];
					break;
				default:
					$_POST['money'] = $_POST['jian'];
					break;
			}
			if ($_POST['timetype']==1) {
				$_POST['open'] = $_POST['openday'];
				$_POST['close'] = $_POST['closeday'];
			}
			$cid = $coupon -> addone($_POST);
			if ($_POST['tiptype']) {
				foreach ($_POST['goodstip'] as $key => $value) {
					$goodstip -> addone($cid,$_POST['goodstip'][$key]);
				}
			}
			$this->redirect("admin/index/coupon");
		}
		$tiplist = $tip -> getall();
		$time = date('Y-m-d',time());
        $this -> assign("tip",$tiplist);
        $this -> assign("date",$time);
		return $this->fetch();
	}
	// 订单
	function order($each=10,$page=1){
		if (request()->isPost()) {
			if ($_POST['ordernum']!='') {
				$str = 'ordernum';
				$arr = $_POST['ordernum'];
				$orders=model("Index")->getlike($str,$arr,0);
			}elseif ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
				$orders=model("Index")->getlikeadd($str,$arr,0);
			}elseif ($_POST['tel']!='') {
				$str = 'tel';
				$arr = $_POST['tel'];
				$orders=model("Index")->getlikeadd($str,$arr,0);
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
				$orders=model("Index")->getlike($str,$arr,0);
			}elseif ($_POST['sendtime']!='') {
				$str = 'sendtime';
				$arr = $_POST['sendtime'];
				$orders=model("Index")->getlike($str,$arr,0);
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
				$orders=model("Index")->getlikeman($str,$arr,0);
			}elseif ($_POST['type']!=0) {
				$str = 'did';
				$arr = $_POST['type'];
				if ($arr==1) {
					$orders=model("Index")->getlikecou(0);
				}else if($arr==2){
					$arr = 0;
					$orders=model("Index")->getlike($str,$arr,0);
				}else{
					$this->redirect('admin/index/order');
				}
			}else{
				$this->redirect('admin/index/order');
			}
			$type = 0;
		}else{
			$type = 1;
			// 分页
			$size = db('orders')->where(['status'=>0])->count();
			$orders=model("Index")->getorders($each,$page,0);
			foreach ($orders as $key => $value) {
				if ($orders[$key]['did']!=0) {
					$mancou = model('mancoupon');
					$coupon = $mancou -> getone($orders[$key]['did']);
					$orders[$key]['did'] = $coupon['name'];
				}else{
					$orders[$key]['did'] = '';
				}
				$orders[$key]['omoney'] = $orders[$key]['omoney']+$orders[$key]['sendmoney'] - $orders[$key]['dismoney'];
			}
			$maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
			$this -> assign('href','order');
	        $this -> assign("each",$each);
	        $this -> assign("size",$size);
	        $this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
		$this -> assign("orders",$orders);
		$this -> assign("type",$type);
		return $this->fetch();
	}
	// 用户
	function user($each=10,$page=1){
		$user = model('user');
		$orders = model('orders');
		$size = db('user')->count();
		if (request()->isPost()) {
			if ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
			}elseif ($_POST['num']!='') {
				$str = 'num';
				$arr = $_POST['num'];
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
			}elseif ($_POST['type']!=0) {
				$str = 'type';
				$arr = $_POST['type'];
			}else{
				$this->redirect('admin/index/user');
			}
			$list = $user ->findway($str,$arr);
			$type = 0;
		}else{
			$type = 1;
			// 分页
	        $maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
	        $list = $user -> getpage($each,$page);
			$this -> assign('href','user');
        	$this -> assign("each",$each);
        	$this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
        foreach ($list as $key => $value) {
        	$list[$key]['lastbuy'] = $orders -> getlastbuy($list[$key]['id']);
        	if ($list[$key]['lastbuy']=='') {
        		$list[$key]['lastbuy'] = '/';
        	}else{
        		$list[$key]['lastbuy'] = date("Y-m-d H:m",$list[$key]['lastbuy']);
        	}
        }
        $sum = $user -> getsum();
        $this -> assign("size",$size);
		$this -> assign('type',$type);
		$this -> assign('sum',$sum);
		$this -> assign('data',$list);
        
		return $this->fetch();
	}
	function usertype($id,$type){
		$user = model('user');
		$user -> changetype($id,$type);
	}
	// 商品
	function good(){
			return $this->fetch();
	}
	// 标签
	function tip(){
			return $this->fetch();
	}
	function make($each=10,$page=1){
		if (request()->isPost()) {
			if ($_POST['ordernum']!='') {
				$str = 'ordernum';
				$arr = $_POST['ordernum'];
				$orders=model("Index")->getlike($str,$arr,1);
			}elseif ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
				$orders=model("Index")->getlikeadd($str,$arr,1);
			}elseif ($_POST['tel']!='') {
				$str = 'tel';
				$arr = $_POST['tel'];
				$orders=model("Index")->getlikeadd($str,$arr,1);
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
				$orders=model("Index")->getlike($str,$arr,1);
			}elseif ($_POST['sendtime']!='') {
				$str = 'sendtime';
				$arr = $_POST['sendtime'];
				$orders=model("Index")->getlike($str,$arr,1);
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
				$orders=model("Index")->getlikeman($str,$arr,1);
			}elseif ($_POST['type']!=0) {
				$str = 'did';
				$arr = $_POST['type'];
				if ($arr==1) {
					$orders=model("Index")->getlikecou(1);
				}else if($arr==2){
					$arr = 0;
					$orders=model("Index")->getlike($str,$arr,1);
				}else{
					$this->redirect('admin/index/make');
				}
			}else{
				$this->redirect('admin/index/make');
			}
			$type = 0;
		}else{
			$type = 1;
			// 分页
			$size = db('orders')->where(['status'=>1])->count();
			$orders=model("Index")->getorders($each,$page,1);
			foreach ($orders as $key => $value) {
				if ($orders[$key]['did']!=0) {
					$mancou = model('mancoupon');
					$coupon = $mancou -> getone($orders[$key]['did']);
					$orders[$key]['did'] = $coupon['name'];
				}else{
					$orders[$key]['did'] = '';
				}
				$orders[$key]['omoney'] = $orders[$key]['omoney']+$orders[$key]['sendmoney'] - $orders[$key]['dismoney'];
			}
			$maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
			$this -> assign('href','make');
	        $this -> assign("each",$each);
	        $this -> assign("size",$size);
	        $this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
		$this -> assign("orders",$orders);
		$this -> assign("type",$type);
		return $this->fetch();
	}
	function give($each=10,$page=1){
		if (request()->isPost()) {
			if ($_POST['ordernum']!='') {
				$str = 'ordernum';
				$arr = $_POST['ordernum'];
				$orders=model("Index")->getlike($str,$arr,2);
			}elseif ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
				$orders=model("Index")->getlikeadd($str,$arr,2);
			}elseif ($_POST['tel']!='') {
				$str = 'tel';
				$arr = $_POST['tel'];
				$orders=model("Index")->getlikeadd($str,$arr,2);
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
				$orders=model("Index")->getlike($str,$arr,2);
			}elseif ($_POST['sendtime']!='') {
				$str = 'sendtime';
				$arr = $_POST['sendtime'];
				$orders=model("Index")->getlike($str,$arr,2);
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
				$orders=model("Index")->getlikeman($str,$arr,2);
			}elseif ($_POST['type']!=0) {
				$str = 'did';
				$arr = $_POST['type'];
				if ($arr==1) {
					$orders=model("Index")->getlikecou(2);
				}else if($arr==2){
					$arr = 0;
					$orders=model("Index")->getlike($str,$arr,2);
				}else{
					$this->redirect('admin/index/give');
				}
			}else{
				$this->redirect('admin/index/give');
			}
			$type = 0;
		}else{
			$type = 1;
			// 分页
			$size = db('orders')->where(['status'=>2])->count();
			$orders=model("Index")->getorders($each,$page,2);
			foreach ($orders as $key => $value) {
				if ($orders[$key]['did']!=0) {
					$mancou = model('mancoupon');
					$coupon = $mancou -> getone($orders[$key]['did']);
					$orders[$key]['did'] = $coupon['name'];
				}else{
					$orders[$key]['did'] = '';
				}
				$orders[$key]['omoney'] = $orders[$key]['omoney']+$orders[$key]['sendmoney'] - $orders[$key]['dismoney'];
			}
			$maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
			$this -> assign('href','give');
	        $this -> assign("each",$each);
	        $this -> assign("size",$size);
	        $this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
		$this -> assign("orders",$orders);
		$this -> assign("type",$type);
		return $this->fetch();
	}
	function com($each=10,$page=1){
		if (request()->isPost()) {
			if ($_POST['ordernum']!='') {
				$str = 'ordernum';
				$arr = $_POST['ordernum'];
				$orders=model("Index")->getlike($str,$arr,3);
			}elseif ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
				$orders=model("Index")->getlikeadd($str,$arr,3);
			}elseif ($_POST['tel']!='') {
				$str = 'tel';
				$arr = $_POST['tel'];
				$orders=model("Index")->getlikeadd($str,$arr,3);
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
				$orders=model("Index")->getlike($str,$arr,3);
			}elseif ($_POST['sendtime']!='') {
				$str = 'sendtime';
				$arr = $_POST['sendtime'];
				$orders=model("Index")->getlike($str,$arr,3);
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
				$orders=model("Index")->getlikeman($str,$arr,3);
			}elseif ($_POST['type']!=0) {
				$str = 'did';
				$arr = $_POST['type'];
				if ($arr==1) {
					$orders=model("Index")->getlikecou(3);
				}else if($arr==2){
					$arr = 0;
					$orders=model("Index")->getlike($str,$arr,3);
				}else{
					$this->redirect('admin/index/com');
				}
			}else{
				$this->redirect('admin/index/com');
			}
			$type = 0;
		}else{
			$type = 1;
			// 分页
			$size = db('orders')->where(['status'=>3])->count();
			$orders=model("Index")->getorders($each,$page,3);
			foreach ($orders as $key => $value) {
				if ($orders[$key]['did']!=0) {
					$mancou = model('mancoupon');
					$coupon = $mancou -> getone($orders['did']);
					$orders[$key]['did'] = $coupon['name'];
				}else{
					$orders[$key]['did'] = '';
				}
				$orders[$key]['omoney'] = $orders[$key]['omoney']+$orders[$key]['sendmoney'] - $orders[$key]['dismoney'];
			}
			$maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
			$this -> assign('href','com');
	        $this -> assign("each",$each);
	        $this -> assign("size",$size);
	        $this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
		$this -> assign("orders",$orders);
		$this -> assign("type",$type);
		return $this->fetch();
	}
	function cancel($each=10,$page=1){
		if (request()->isPost()) {
			if ($_POST['ordernum']!='') {
				$str = 'ordernum';
				$arr = $_POST['ordernum'];
				$orders=model("Index")->getlike($str,$arr,5);
			}elseif ($_POST['name']!='') {
				$str = 'name';
				$arr = $_POST['name'];
				$orders=model("Index")->getlikeadd($str,$arr,5);
			}elseif ($_POST['tel']!='') {
				$str = 'tel';
				$arr = $_POST['tel'];
				$orders=model("Index")->getlikeadd($str,$arr,5);
			}elseif ($_POST['ordertime']!='') {
				$str = 'ordertime';
				$arr = $_POST['ordertime'];
				$orders=model("Index")->getlike($str,$arr,5);
			}elseif ($_POST['sendtime']!='') {
				$str = 'sendtime';
				$arr = $_POST['sendtime'];
				$orders=model("Index")->getlike($str,$arr,5);
			}elseif ($_POST['userid']!='') {
				$str = 'userid';
				$arr = $_POST['userid'];
				$orders=model("Index")->getlikeman($str,$arr,5);
			}elseif ($_POST['type']!=0) {
				$str = 'did';
				$arr = $_POST['type'];
				if ($arr==1) {
					$orders=model("Index")->getlikecou(5);
				}else if($arr==2){
					$arr = 0;
					$orders=model("Index")->getlike($str,$arr,5);
				}else{
					$this->redirect('admin/index/cancel');
				}
			}else{
				$this->redirect('admin/index/cancel');
			}
			$type = 0;
		}else{
			$type = 1;
			// 分页
			$size = db('orders')->where(['status'=>5])->count();
			$orders=model("Index")->getorders($each,$page,5);
			foreach ($orders as $key => $value) {
				if ($orders[$key]['did']!=0) {
					$mancou = model('mancoupon');
					$coupon = $mancou -> getone($orders['did']);
					$orders[$key]['did'] = $coupon['name'];
				}else{
					$orders[$key]['did'] = '';
				}
				$orders[$key]['omoney'] = $orders[$key]['omoney']+$orders[$key]['sendmoney'] - $orders[$key]['dismoney'];
			}
			$maxPage = ceil($size/$each);
	        $page = getPage($maxPage,$page);
	        $pages = getPages($maxPage,$page);
			$this -> assign('href','cancel');
	        $this -> assign("each",$each);
	        $this -> assign("size",$size);
	        $this -> assign("maxPage",$maxPage);
	        $this -> assign("pages",$pages);
	        $this -> assign("page",$page);
		}
		$this -> assign("orders",$orders);
		$this -> assign("type",$type);
		return $this->fetch();
	}
	function mc(){
		db("orders")->where('id',$_GET['id'])->update(["status"=>2]);
		$this->redirect("admin/index/give");
	}
	function gc(){
		db("orders")->where('id',$_GET['id'])->update(["status"=>3]);
		$this->redirect("admin/index/com");
	}
	function printf($arr=0){
		$arr = json_decode($arr);
		if ($arr==0) {
			exit("<script>alert('未选择所需打印的标签的订单，请到订单中选择');window.location.href='make';</script>");
		}else{
			foreach ($arr as $key => $value) {
				$orders=model("Index")->print($arr[$key]);
				$good=model("Index")->printgood($arr[$key]);
				$newarr[$key] = $orders;
				$newarr[$key]['good'] = $good;
			}
			$this->assign('order',$newarr);
		}
		return $this->fetch();
	}
	function sendman(){
		$result = model('sendman')->getall();
		if (request()->isPost()) {
			$send = model('sendman');
			$result = $send->changetype($_POST['id'],$_POST['type']);
			return $result;
		}
		$this->assign('data',$result);
		return $this->fetch();
	}
}