<?php
namespace app\admin\model;
use think\Model;

class Coupon extends Model
{
	function getall(){
		$result = db('Coupon')->select();
		return $result;
	}
	function getpage($each,$page){
		$result = db('Coupon')->limit($each)->page($page)->select();
		return $result;
	}
	function getid($id){
		$result = db('Coupon')->where(['id'=>$id])->find();
		return $result;
	}
	function addone($data){
		$data = ['name'=>$data['name'],'content'=>$data['extra'],'condition'=>$data['conditon'],'moneytype'=>$data['moneytype'],'money'=>$data['money'],'number'=>$data['number1'],'timetype'=>$data['timetype'],'open'=>$data['open'],'close'=>$data['close'],'type'=>$data['type'],'tiptype'=>$data['tiptype'],'limi'=>$data['limit'],'status'=>0];
		db('Coupon')->insert($data);	
		$result = db('Coupon') -> getLastInsId();
		$link = 'http://xwc.enj1.com/public/index.php/index/bargin/yhq?id='.$result;
		db('Coupon') ->where(['id'=>$result])->update(['link'=>$link]);
		return $result;
	}
	function changesta($id,$status){
		$result = db('Coupon')->where(["id"=>$id])->update(["status"=>$status]);
		return $result;
	}
}