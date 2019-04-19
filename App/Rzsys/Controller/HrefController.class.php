<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class HrefController extends AllowController
{
    
	//列表
	 function alist(){
		
		$User = M('mage_look_url'); // 实例化User对象
		
		
	
		$guige = $User->where('IS_DEL=0')->order('ID desc')->select();
		$this->assign('guige',$guige);// 赋值数据集
		$type=['1'=>'可用','2'=>'已停用'];
		
		$this->assign('type',$type);
		
		$this->display(); // 输出模板
	}
	//添加
	 function add(){
		//获取请求参数
		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mage_look_url')->find($id);
		}
		
        $this->assign('data',$data);// 赋值数据集
		$this->display(); // 输出模板
	}
	//规格提交
	 function doadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['LU_NAME']=$_POST['name'];
		$data['LU_REALM']=$_POST['href'];
		$data['LU_OTHER']=$_POST['other'];
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mage_look_url')->where("ID={$id}")->save($data);
			$this->addlog('2','域名修改');
			$da['msg']="修改成功";
			$da['href']=U("href/alist");
		}else{
			$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_look_url')->add($data);
				$this->addlog('1','域名添加');
				$da['msg']="添加成功";
				$da['href']=U("href/alist");
		}
		
		echo json_encode($da);
	}
	//停用
	public function fenghao(){
		//获取请求参数
		$id = $_GET['id'];
		$state = $_GET['state'];
		
		
		if($state==1){
			$state=2;
		}else{
			if($state==2){
				$state=1;
			}
		}
		if(!empty($id)){
			M('mage_look_url')->where("ID=$id")->save(['STATE'=>$state]);
			//echo M('mage_look_url')->getlastsql();die();
			   $da['msg']="成功";
			  $da['href']=U("href/alist");
		}else{
			 $da['msg']="失败";
			$da['href']=U("href/alist");
		}
		echo json_encode($da);
	}
	//删除
	 function del(){
		//获取请求参数
		$id = $_GET['id'];
		if(!empty($id)){
			M('mage_look_url')->where("ID=$id")->save(['IS_DEL'=>1]);
			 $da['msg']="删除成功";
			 $this->addlog('3','域名删除');
			  $da['href']=U("href/alist");
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("href/alist");
		}
		echo json_encode($da);
	}
	
}
