<?php
namespace app\index\model;
use think\Model;

class Sendman extends Model
{
	function add($data){
		$result = db('Sendman')->where(['openid'=>$data['openid']])->find();
		if ($result) {
			return;
		}
		$data = ['head'=>$data['headimgurl'],'openid'=>$data['openid'],'name'=>$data['nickname']];
		db('Sendman')->insert($data);
	}
	function getsend(){
		$result = db('Sendman')->where(['type'=>1])->select();
		return $result;
	}


	
}