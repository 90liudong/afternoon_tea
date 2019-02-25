<?php
namespace app\admin\model;
use think\Model;

class Mancoupon extends Model
{
	function getlimi($id,$uid){
		$result = db('Mancoupon')->where(["cid"=>$id,"uid"=>$uid])->select();
		return $result;
	}
	function addone($cid,$uid){
		$data = ['cid'=>$cid,'uid'=>$uid,'time'=>time()];
		db('Mancoupon')->insert($data);
	}
	function getman($id){
		$result = db('Mancoupon')->alias('c')->field('c.*,u.name,u.content,u.timetype,u.type,u.condition,u.open,u.close,u.moneytype,u.money,u.tiptype')->join('coupon u','u.id=c.cid')->where(['uid'=>$id])->order('id desc')->select();
		return $result;
	}
	function getman1($id){
		$result = db('Mancoupon')->alias('c')->field('c.*,u.id did,u.name,u.content,u.timetype,u.type,u.condition,u.open,u.close,u.moneytype,u.money,u.tiptype')->join('coupon u','u.id=c.cid')->where(['uid'=>$id])->order('id desc')->select();
		foreach ($result as $k => $value) {
			$data = db('cougood')->field("tid")->where(['cid'=>$result[$k]['cid']])->select();
			if (empty($data)) {
				$result[$k]['tip'] = $data;
			}
			foreach ($data as $key => $value) {
				$data1 = db('goodstip')->alias('t')->field('g.name')->join('goods g','t.gid=g.id')->where(['tid'=>$data[$key]['tid']])->select();
				foreach ($data1 as $e => $value) {
					$tip[] = $data1[$e]['name'];
				}
				$tip = array_unique($tip);
				$result[$k]['tip'] = $tip;
			}
			unset($tip);
		}
		return $result;
	}
	function changesta($id,$status){
		$result = db('Mancoupon')->where(["id"=>$id])->update(["status"=>$status]);
		return $result;
	}
	function getone($did){
		$result = db('Mancoupon')->where(["id"=>$did])->find();
		$result = db('Coupon')->where(['id'=>$result['cid']])->find();
		return $result;
	}
}