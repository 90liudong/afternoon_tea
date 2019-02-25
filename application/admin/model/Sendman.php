<?php
namespace app\admin\model;
use think\Model;

class Sendman extends Model
{
	function getall(){
		$result = db('Sendman')->select();
		return $result;
	}
	function changetype($id,$type){
		$result = db('Sendman')->where(["id"=>$id])->update(["type"=>$type]);
		return $result;
	}
}