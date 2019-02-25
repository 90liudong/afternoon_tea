<?php
namespace app\admin\model;
use think\Model;

class Cougood extends Model
{
	function addone($cid,$tid){
		$data = ['cid'=>$cid,'tid'=>$tid];
		db('cougood')->insert($data);
	}
}