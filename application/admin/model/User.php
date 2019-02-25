<?php
namespace app\admin\model;
use think\Model;

class User extends Model
{
	function getall(){
		$result = db('User')->select();
		return $result;
	}
	function getpage($each,$page){
		$result = db('User')->limit($each)->page($page)->select();
		return $result;
	}
	function getid($id){
		$result = db('User')->where(['id'=>$id])->find();
		return $result;
	}
	function changetype($id,$type){
		$result = db('User')->where(["id"=>$id])->update(["type"=>$type]);
		return $result;
	}
	function getsum(){
		$result = db('User')->where(['num'=>["neq",0]])->sum('num');
		return $result;
	}
	function findway($str,$arr){
		$result = db('User')->where("$str",'like','%'.$arr.'%')->select();
		return $result;
	}
}