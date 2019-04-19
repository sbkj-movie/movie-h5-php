<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class TaocanController extends AllowController
{
    //加载后台模板
    public function alist()
	{   
		$User = M('mage_shop'); // 实例化User对象
		$where='1';
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		$list = $User->where('IS_DEL=0')->order('ID desc')->page("$p,25")->select();
		$this->assign('ad',$list);// 赋值数据集
		$count      = $User->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
		
		$this->display(); // 输出模板 
    }
	//管理员添加
	public function add()
	{   
		//获取请求参数
		$id = $_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mage_shop')->find($id);
		}
		$data['pg']=$_GET['pg'];
      $this->assign('data',$data);
	
		$this->display(); // 输出模板
    }
	//管理员提交
	public function doadd(){
	
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['SP_NAME']=$_POST['name'];
		$data['SP_PRICE']=$_POST['price'];
		$data['SP_END_TIME']=$_POST['mtime'];
	
	
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mage_shop')->where("ID=$id")->save($data);
			$this->addlog('2','套餐修改');
			$da['msg']="修改成功";
			$da['href']=U("taocan/alist")."?p=$pg";
			
		}else{
				
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_shop')->add($data);
				$this->addlog('1','套餐添加');
				$da['msg']="添加成功";
				$da['href']=U("taocan/alist")."?p=$pg";
		}
		echo json_encode($da);
		
		 
	}
	//删除
	public function del(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			M('mage_shop')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','套餐删除');
			   $da['msg']="删除成功";
			 $da['href']=U("taocan/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("taocan/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	

}
