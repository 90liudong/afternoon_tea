<?php
namespace app\index\controller;
use think\Controller;

class Like extends Controller{

	function index(){
		return $this->fetch();
	}
	function pingjia($id,$txt,$good){
		$order = model('orders');
		$order -> updategood($id,$txt,$good);
		return 1;
	}
}