<?php
namespace app\admin\model;
use think\Model;

class Goods extends Model
{
	function getAllGoods(){
		$result = db("goods")->field("id")->order("id desc")->select();
		return $result;
	}
	function getAllGoodsDetail($each,$page){
		$result = db("goods")->limit($each)->page($page)->order("id desc")->select();
		return $result;
	}
	function delSome($arr){
		for ($i=0; $i <count($arr) ; $i++) {
			db("goods")->where(["id"=>$arr[$i]])->delete();
			db("goodstip")->where(["gid"=>$arr[$i]])->delete();
		}
		
	}
	function search($num){
		$result = db("goods")->where(["id"=>$num])->find();
		return $result;
	}
	function searchName($name){
		$result = db("goods")->where(["name"=>$name])->find();
		return $result;
	}
	function getOne($gid){
		$result = db("goods")->where(["id"=>$gid])->find();
		return $result;
	}
	function getThouse($gid){
		$result = db("goodstip")->field("tid")->where(["gid"=>$gid])->select();
		$havetip = [];
		for ($i=0; $i <count($result) ; $i++) { 
			$havetip[] = $result[$i]["tid"];
		}
		return $havetip;

	}
}