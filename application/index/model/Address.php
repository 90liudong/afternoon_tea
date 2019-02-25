<?php
namespace app\index\model;
use think\Model;

class Address extends Model
{
	function getone(){
		$result = db('Address')->where(['uid'=>session('uid')])->select();
		return $result;
	}
	function gettype(){
		$result = db('Address')->where(['type'=>1])->find();
		return $result;
	}
	function getadd($id){
		$result = db('Address')->where(['id'=>$id])->find();
		return $result;
	}
	function getuid($id){
		$result = db('Address')->where(['uid'=>$id,'type'=>1])->order('id desc')->find();
		return $result;
	}
	function changesta($id){
		$result = db('Address')->where(["uid"=>session('uid'),'type'=>1])->update(["type"=>0]);
		$result = db('Address')->where(["id"=>$id])->update(["type"=>1]);
		return $result;
	}
	function addupdate($data){
		$result = db('Address')->where(['id'=>$data['id']])->update(['name'=>$data['name'],'tel'=>$data['tel'],'address'=>$data['callback'],'doornumber'=>$data['door'],'uid'=>$data['uid'],'lat'=>$data['location_callback']]);
		return $result;
	}
	function add($data){
		$result = db('Address')->where(["uid"=>$data['uid'],'type'=>1])->update(["type"=>0]);
		$result = db('Address')->insert(['name'=>$data['name'],'tel'=>$data['tel'],'address'=>$data['callback'],'doornumber'=>$data['door'],'uid'=>$data['uid'],'lat'=>$data['location_callback']]);
		return $result;
	}
}