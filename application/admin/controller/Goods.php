<?php
namespace app\admin\controller;
use think\Controller;

Class Goods extends Controller{
	function sale($each=10,$page=1){
		$shops = model("Goods");
		$size = $shops->getAllGoods();
	
		$amount =  count($size);
		$maxPage = ceil($amount/$each);
		$page=getPage($maxPage,$page);
		$pages=getPages($maxPage,$page);
		$href = 'sale?';
		$data = $shops->getAllGoodsDetail($each,$page);
		for ($i=0; $i <count($data) ; $i++) { 
			$data[$i]["idd"] = sprintf("%04d", $data[$i]["id"]);
		}
		// mp($data);
		$this->assign("each",$each);
		$this->assign("maxPage",$maxPage);
		$this->assign("pages",$pages);
		$this->assign("page",$page);
		$this->assign("href",$href);
		$this->assign("amount",$amount);
		$this->assign("data",$data);
		return $this->fetch();
	}
	
	function search($num){
	
		$num+=0;
		// echo $num;exit;
		$shops = model("Goods");
		$data = $shops->search($num);
		$data["idd"] = sprintf("%04d", $data["id"]);
		$data["time"] = date("Y-m-d H:i:s", $data["time"]);
		return $data;
	}
	function searchName($name){
		$shops = model("Goods");
		$data = $shops->searchName($name);
		$data["idd"] = sprintf("%04d", $data["id"]);
		$data["time"] = date("Y-m-d H:i:s", $data["time"]);
		return $data;
	}
	function edit($sign=1){
		if ($sign==1) {
			$tip = model("Tip");
			$tiplist = $tip -> getall();
	 		$this -> assign("tip",$tiplist);
			return $this->fetch();
		}
		
	}
	function gedit($gid){
		$tip = model("Tip");
		$tiplist = $tip -> getall();
 		
 		$shops = model("Goods");
		$data = $shops->getOne($gid);
		$havetip = $shops->getThouse($gid);
		// mp($tiplist);

		$tippp = [];
		$tipss = [];
		$sign = false;
		for ($i=0; $i <count($tiplist) ; $i++) { 
			
				if (!in_array($tiplist[$i]["id"],$havetip)) {
					$tippp[]=$tiplist[$i];
				}else{
					$tipss[]=$tiplist[$i];	
				}
			
		}
		// mp($tippp);
		$this->assign("data",$data);
		$this -> assign("tippp",$tippp);
		$this->assign("tipss",$tipss);
		$this->assign("gid",$gid);

		return $this->fetch();
	}
	function addOneGood($title,$goodstip,$nowprice,$yuanprice=0,$number,$account,$limitnum,$typee){
		// echo $title.",".$nowprice.","."yuanjia ".$yuanprice.",".$number.",".$account.",".$limitnum;
		if($number){

		}else{
			$number=-1;
		}
		if($limitnum){

		}else{
			$limitnum=0;
		}
		// mp($goodstip);exit;
		    // 获取表单上传文件 例如上传了001.jpg
		    $file = request()->file('file');
		    // mp($file);
		    // 移动到框架应用根目录/public/uploads/ 目录下
		    if($file){
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS . 'goodimage');
		            // mp($info);
		        if($info){
		            
		            $image =  $info->getSaveName();
		            // mp($image);
		          
		        }else{
		            // 上传失败获取错误信息
		            echo $file->getError();
		        }
		    }
		// mp($_POST);
		db("goods")->insert(["name"=>$title,"money"=>$nowprice,"newmoney"=>$yuanprice,"img"=>$image,"sendmoney"=>$account,"number"=>$number,"type"=>$limitnum,"time"=>time(),"typee"=>$typee]);
		$gid = db("goods")->getLastInsID();
		for ($i=0; $i <count($goodstip) ; $i++) { 
			db("goodstip")->insert(["gid"=>$gid,"tid"=>$goodstip[$i]]);
		}
		$this->redirect("sale");
	}
	function updateOneGood($gid,$imagegg,$title,$goodstip,$nowprice,$yuanprice=0,$number,$account,$limitnum,$typee){
		// echo $title.",".$nowprice.","."yuanjia ".$yuanprice.",".$number.",".$account.",".$limitnum;
		if($number){

		}else{
			$number=-1;
		}
		if($limitnum){

		}else{
			$limitnum=0;
		}
		// mp($goodstip);exit;
		    // 获取表单上传文件 例如上传了001.jpg
		    $file = request()->file('file');
		    
		    // 移动到框架应用根目录/public/uploads/ 目录下
		    if($file){
		        $info = $file->move(ROOT_PATH . 'public' . DS . 'static'. DS . 'goodimage');
		        if($info){
		           
		            $image =  $info->getSaveName();
		          
		        }else{
		            // 上传失败获取错误信息
		            echo $file->getError();
		        }
		    }else{
		    	$image = $imagegg;
		    }
		
		db("goods")->where(["id"=>$gid])->update(["name"=>$title,"money"=>$nowprice,"newmoney"=>$yuanprice,"img"=>$image,"sendmoney"=>$account,"number"=>$number,"type"=>$limitnum]);
		db("goodstip")->where(["gid"=>$gid])->delete();
		for ($i=0; $i <count($goodstip) ; $i++) { 
			db("goodstip")->insert(["gid"=>$gid,"tid"=>$goodstip[$i]]);
		}
		$this->redirect("sale");
	}
	function delSome($array){
		$shops = model("Goods");
		$shops->delSome($array);
	}
}