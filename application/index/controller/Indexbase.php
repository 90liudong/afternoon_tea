<?php
namespace app\index\controller;
class Indexbase extends \think\Controller{
	function _initialize(){
		if (!session("?openid")) {
        	$this ->redirect('index/indexlogin/index');
        	exit;
        }
	}
}