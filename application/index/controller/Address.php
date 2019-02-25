<?php
namespace app\index\controller;
use think\Controller;

class Address extends Controller{

	function index(){
		$add = model('address');
	 	$data =  $add -> getone();
		$this->assign('data',$data);
		return $this->fetch();
	}
	function change(){
        if (request()->isPost()) {
			$add = model('address');
			$add -> changesta($_POST['id']);
			return 1;
		}
		return $this->fetch();
	}
	function add(){
		$add = model('address');
		$add -> add($_POST);
        $this ->redirect('index/orderqueren/index');
	}
	function changeadd(){
		$add = model('address');
		$add -> addupdate($_POST);
        $this ->redirect('index/address/index');
	}
	function change1($aid){
		$add = model('address');
		$data = $add -> getadd($aid);
        $this->assign('str',json_encode($data));
		$this->assign('data',$data);
		return $this->fetch();
	}
}