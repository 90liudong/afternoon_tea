<?php
namespace app\admin\model;
use think\Model;

class Tip extends Model
{
	function getall(){
		$result = db('tip')->where(['type'=>1])->order('list asc')->select();
		return $result;
	}
	function addOne($name,$list){
		$result = db("tip")->insert(["name"=>$name,"list"=>$list]);
		return db("tip")->getLastInsID();
	}
	function getList(){
		$result = db("tip")->count();
		return $result+1;
	}
	function getTip(){
		$result = db("tip")->order("list asc")->select();
		return $result;
	}
	function Show($tid,$type){
		db("tip")->where(["id"=>$tid])->update(["type"=>$type]);
	}
	function delOne($tid){
		$result = db("tip")->field("list")->where(["id"=>$tid])->find();
		$listid = $result["list"];
		db("tip")->where(["id"=>$tid])->delete();
		$data = db("tip")->field("list")->where(["list"=>["gt",$listid]])->select();
		for($i=0;$i<count($data);$i++){
			db("tip")->where(["list"=>$data[$i]["list"]])->update(["list"=>$data[$i]["list"]-1]);
		}
	}
	function changeName($tid,$name){
		db("tip")->where(["id"=>$tid])->update(["name"=>$name]);
	}
	function changeListUp($tid1,$tid2,$list,$list2){
		db("tip")->where(["id"=>$tid1])->update(["list"=>$list-1]);
		db("tip")->where(["id"=>$tid2])->update(["list"=>$list2+1]);

	}
	function changeListDown($tid1,$tid2,$list,$list2){
		db("tip")->where(["id"=>$tid1])->update(["list"=>$list+1]);
		db("tip")->where(["id"=>$tid2])->update(["list"=>$list2-1]);

	}
}