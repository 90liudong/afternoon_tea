<?php
namespace app\index\model;
use think\Model;

class Coupon extends Model
{
	function getone($id){
		$result = db('Coupon')->where(['id'=>$id])->find();
		return $result;
	}
	function changesta($id,$status){
		$result = db('Coupon')->where(["id"=>$id])->update(["status"=>$status]);
		return $result;
	}
	function downnumber($id,$number){
		$result = db('Coupon')->where(["id"=>$id])->update(["number"=>$number]);
		return $result;	
	}
	function getuid($uid){
		$result = db('Coupon')->where(["uid"=>$uid])->select();
		return $result;	
	}
}