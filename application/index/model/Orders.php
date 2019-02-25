<?php
namespace app\index\model;
use think\Model;

class Orders extends Model
{
	function getone($id){
		$result = db('Orders')->where(['id'=>$id])->find();
		return $result;
	}
	function getmanorder($id){
		$result = db('Orders')->where(['uid'=>$id])->order('id desc')->select();
		return $result;
	}
	function changesta($id,$status){
		$result = db('Orders')->where(["id"=>$id])->update(["status"=>$status]);
		return $result;
	}
	function getnotpay(){
		$result = db('Orders')->where(["status"=>0])->select();
		return $result;
	}
	function deleid($id){
		$result = db('Orders')->where(["id"=>$id])->delete();
		return $result;
	}
	function updategood($id,$txt,$good){
		$result = db('Orders')->where(["id"=>$id])->update(['pingjia'=>$txt,'status'=>4,'good'=>$good]);
		return $result;
	}
	function addorder($data,$aid){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->find();
		db('Orders')->where(["id"=>$result['id']])->delete();
		db('ordergoods')->where(["oid"=>$result['id']])->delete();
		$str = 'A'.date('YmdHms',time()).rand(1000,9999);
		$result = db('Orders')->insert(['ordernum'=>$str,'money'=>$data['money'],'num'=>$data['number'],'aid'=>$aid,'ordertime'=>time(),'status'=>8,'uid'=>session('uid')]);
		$result = db('Orders') -> getLastInsId();
		foreach ($data['data'] as $key => $value) {
			db('ordergoods') -> insert(['oid'=>$result,'gid'=>$data['data'][$key]['gid'],'num'=>$data['data'][$key]['num'],'money'=>$data['data'][$key]['money'],'name'=>$data['data'][$key]['name']]);
		}
	} 
	function getorder(){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->find();
		$data = db('ordergoods')-> where(["oid"=>$result['id']])->order('money asc')->select();
		$result['good'] = $data;
		return $result;
	}
	function getorder1($data){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->find();
		db('Orders')->where(["uid"=>session('uid'),'status'=>8])->update(['sendtime'=>$data['sendtime'],'sendmoney'=>$data['sendmoney']]);
		$data = db('ordergoods')->alias('o')->field('o.*,g.number')-> where(["oid"=>$result['id']])->join('goods g','g.id=o.gid')->order('money asc')->select();
		foreach ($data as $key => $value) {
            if ($data[$key]['number']==0) {
                exit("<script>alert('对不起，" . $data[$key]['name'] . "已经卖光了！');window.location.href='../../index/pro/index.html';</script>");
            }else{
				db('goods')->where(['id'=>$data[$key]['gid']])->update(["number"=>$data[$key]['number']-1]);
            }
        }
		$result['good'] = $data;
		return $result;
	}
	function getorder2($id){
		$result = db('Orders')->where(['id'=>$id])->find();
		$data = db('ordergoods')-> where(["oid"=>$result['id']])->order('money asc')->select();
		$result['good'] = $data;
		return $result;
	}
	function getorder4(){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>1])->find();
		$data = db('ordergoods')-> where(["oid"=>$result['id']])->order('money asc')->select();
		$result['good'] = $data;
		return $result;
	}
	function getorder3($cid){
		$result = db('Orders')->where(['id'=>$cid])->find();
		$data = db('ordergoods')-> where(["oid"=>$result['id']])->order('money asc')->select();
		$data1 = db('address')-> where(["id"=>$result['aid']])->find();
		if ($result['did']!=0) {
			$mancou = model('mancoupon');
			$coupon = $mancou -> getone($result['did']);
			$cou = $coupon['name'];
		}else{
			$cou = '没有使用优惠券。';
		}
		$result['cou'] = $cou;
		$result['add'] = $data1;
		$result['good'] = $data;
		return $result;
	}
	function getoid($status){
		$result = db('Orders')->field('id')->where(['uid'=>session('uid'),'status'=>$status])->order('id desc')->select();
		return $result;
	}
	function changecou($cid,$money){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->update(['did'=>$cid,'dismoney'=>$money]);
		return $result;
	}
	function deleteorder(){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->delete();
	}
	function upextra($str){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->update(['extra'=>$str]);
		return $result;
	}
	function getextra(){
		$result = db('Orders')->where(["uid"=>session('uid'),'status'=>8])->find();
		return $result;
	}
}