<?php
namespace app\index\model;
use think\Model;

class Goods extends Model
{
	function getone($id){
		$result = db('goods')->where(['id'=>$id])->find();
		return $result;
	}
	function updatenum($id,$num){
		$result = db('goods')->where(['id'=>$id])->find();
		$result = db('goods')->where(['id'=>$id])->update(["number"=>$result['number']+$num]);
		return $result;
	}
	function getgoodss(){
		$result = db("goods")->field('goods.name,goods.money,goods.img,goods.typee,goods.number,goods.id,goods.newmoney,goods.type')->where(["goods.typee"=>1])->order('id desc')->find();
		
		// mp($result);
		// mp($result[1]["goods"]);
		return $result;
	}
}