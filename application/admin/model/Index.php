<?php
namespace app\admin\model;
use think\Model;
use think\Db;
class Index extends Model{
	function getorders($each,$page,$sta){
		$res=db("orders")->alias("o")->field("o.id,o.ordernum,o.money omoney,o.id,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.name aname,a.tel,u.img,u.name")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.uid","left")->limit($each)->page($page)->where(["status"=>$sta])->select();
		for($i=0;$i<count($res);$i++){
			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num,g.img")->join("orders o","o.id=og.oid","left")->join("goods g","g.id=og.gid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}
	function getmake($each,$page){
		$res=db("orders")->alias("o")->field("o.ordernum,o.id,o.money omoney,o.dismoney,o.did,o.ordertime,o.sendmoney,o.ordertime,o.sendtime,a.address,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->limit($each)->page($page)->where("status=1")->select();
		for($i=0;$i<count($res);$i++){
			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}
	function getgive($each,$page){

		$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.dismoney,o.did,o.ordertime,o.sendmoney,o.id,o.ordertime,o.sendtime,a.address,a.doornumber,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->limit($each)->page($page)->where("status=2")->select();
		// $res = count($res);
		// return $res;
		for($i=0;$i<count($res);$i++){

			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}

	function getcom($each,$page){

		$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.doornumber,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->limit($each)->page($page)->where("status=3")->select();
		// $res = count($res);
		// return $res;
		for($i=0;$i<count($res);$i++){

			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}

	function getcancel($each,$page){

		$res=db("orders")->alias("o")->field("o.ordernum,o.id,o.money omoney,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.doornumber,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->limit($each)->page($page)->where("status=4")->select();
		// $res = count($res);
		// return $res;
		for($i=0;$i<count($res);$i++){

			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}
	function getlike($str,$arr,$status){
		$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->where(['status'=>$status,"$str"=>$arr])->select();
		for($i=0;$i<count($res);$i++){
			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}
	function getlikecou($status){
		$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->where(['status'=>$status])->where('did','neq',0)->select();
		for($i=0;$i<count($res);$i++){
			$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
			$res[$i]['ordergoods']=$ordergoods;
		}
		return $res;
	}
	function getlikeadd($str,$arr,$status){
		$res1 = db('address')->where(["$str"=>$arr])->select();
		if (empty($res1)) {
			return $res1;
		}
		foreach ($res1 as $key => $value) {
			$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->where(['status'=>$status,'aid'=>$res1[$key]['id']])->select();
			for($i=0;$i<count($res);$i++){
				$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->select();
				$res[$i]['ordergoods']=$ordergoods;
			}
		}
		return $res;
	}
	function getlikeman($str,$arr,$status){
		$res1 = db('user')->where(["$str"=>$arr])->select();
		if (empty($res1)) {
			return $res1;
		}
		foreach ($res1 as $key => $value) {
			$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.dismoney,o.did,o.ordertime,o.sendmoney,o.sendtime,a.address,a.name aname,a.tel,u.img,u.openid")->join("address a","o.aid=a.id","left")->join("user u","u.id=o.id","left")->where(['status'=>$status,'uid'=>$res1[$key]['id']])->select();
			for($i=0;$i<count($res);$i++){
				$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$res[$i]["id"])->select();
				$res[$i]['ordergoods']=$ordergoods;
			}
		}
		return $res;
	}
	// function print($id){
	// 	$res=db("orders")->alias("o")->field("o.ordernum,o.money omoney,o.id,o.ordertime,o.sendtime,o.extra,a.address,a.name aname,a.tel")->join("address a","o.aid=a.id","left")->where('o.id',$id)->find();
	// 	return $res;
	// }
	function printgood($id){
		$ordergoods=db("ordergoods")->alias("og")->field("og.name,og.money,og.num")->join("orders o","o.id=og.oid","left")->where('og.oid',$id)->select();
		return $ordergoods;
	}
}