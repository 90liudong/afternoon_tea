<?php
namespace app\index\model;
use think\Model;

class User extends Model
{
	function getone($id){
		$result = db('user')->where(['id'=>$id])->find();
		return $result;
	}
	function findman($id){
		$result = db('user')->where(['openid'=>$id])->find();
		return $result;
	}
	function findlastid(){
		$man = db('user')->order('id desc')->find();
		return $man['id'];	
	}
	function add($data){
		$data = ['img'=>$data['headimgurl'],'openid'=>$data['openid'],'name'=>$data['nickname'],'userid'=>$data['userid']];
		db('user')->insert($data);
		$result = db('user')->getLastInsID();
		return $result;
	}
}