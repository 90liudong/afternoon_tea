<?php
namespace app\admin\model;
use think\Model;

class Orders extends Model
{
	function getlastbuy($id){
		$result = db('orders')->where(['id'=>$id])->order('id desc')->find('ordertime');
		return $result;
	}
}