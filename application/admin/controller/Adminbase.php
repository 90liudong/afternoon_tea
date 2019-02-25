<?php
namespace app\admin\controller;
class Adminbase extends \think\Controller{
	function _initialize(){
		if (!session('?admin-tea')) {
			$this->redirect("admin/adminlogin/index");
			exit;
        }
	}
}
 