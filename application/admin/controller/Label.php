<?php
namespace app\admin\controller;
use think\Controller;

Class Label extends Controller{
	function manage(){
		$tip = model("Tip");
		$data = $tip->getTip();
		$this->assign("data",$data);
		return $this->fetch();
	}
	function addOne($name){
		$tip = model("Tip");
		$list = $tip->getList();
		$tid=$tip->addOne($name,$list);
		$data = [$tid,$name,$list];
		return $data;
	}
	function Show($tid,$type){
		$tip = model("Tip");
		$tip->Show($tid,$type);
	}
	function delOne($tid){
		$tip = model("Tip");
		$tip->delOne($tid);
	}
	function changeName($tid,$name){
		$tip = model("Tip");
		$tip->changeName($tid,$name);
		echo 1;
	}
	function changeListUp($tid1,$tid2,$list,$list2){
		$tip = model("Tip");
		$tip->changeListUp($tid1,$tid2,$list,$list2);
		echo 1;
	}
	function changeListDown($tid1,$tid2,$list,$list2){
		$tip = model("Tip");
		$tip->changeListDown($tid1,$tid2,$list,$list2);
		echo 1;
	}
}