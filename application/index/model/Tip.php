<?php
namespace app\index\model;
use think\Model;

class Tip extends Model
{
	function gettip(){
		$result = db('Tip')->where(['type'=>1])->order('list asc')->select();
		// mp($result);
		return $result;
	}
	function getgoods(){
		$result = db("tip")->field('tip.name,tip.id')->where(["type"=>1])->order('list asc')->select();

		for ($i=0; $i <count($result) ; $i++) { 
			$result1 = db('goodstip')->alias('gs')->field('g.name,g.money,g.img,g.id,g.typee,g.newmoney,g.type,g.number')->where(["gs.tid"=>$result[$i]["id"],"g.typee"=>0])->join('goods g','g.id = gs.gid','left')->select();
			$result[$i]["goods"]=$result1;
		}
		// mp($result);
		// mp($result[0]["id"]);
		return $result;
	}

	


	 
	
}