<?php
namespace app\index\model;
use think\Model;

class Ordergoods extends Model
{
	function getfirstgood($id){
		$result = db('Ordergoods')->where(['oid'=>$id])->find();
		return $result;
	}
	function getordergoods($id){
		$result = db('Ordergoods')->where(['oid'=>$id])->select();
		return $result;
	}
	function deleoid($id){
		$result = db('Ordergoods')->where(['oid'=>$id])->delete();
		return $result;
	}
}