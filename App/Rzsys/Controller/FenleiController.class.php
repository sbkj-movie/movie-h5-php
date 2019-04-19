<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class FenleiController extends AllowController
{
    
	//规格列表
	 function alist(){
		
		$User = M('mv_class'); // 实例化User对象
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
		$guige = $User->where('IS_DEL=0')->order('ID desc')->select();
		
		$this->assign('guige',$guige);// 赋值数据集
		
		$this->display(); // 输出模板
	}
	//规格添加
	 function classadd(){
		//获取请求参数
		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mv_class')->find($id);
		}
		
        $this->assign('data',$data);// 赋值数据集
		$this->display(); // 输出模板
	}
	//规格提交
	 function doguige(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['CL_NAME']=$_POST['name'];
		$data['CL_PIC']=$_POST['pic'];
		$data['CL_TYPE']=$_POST['type'];
		$data['CL_NUM']=$_POST['clnum'];
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mv_class')->where("ID={$id}")->save($data);
		    //echo 
			$this->addlog('2','分类修改');
			$da['msg']="修改成功";
			$da['href']=U("fenlei/alist");
		}else{
			$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mv_class')->add($data);
				$this->addlog('1','分类添加');
				$da['msg']="添加成功";
				$da['href']=U("fenlei/alist");
		}
		
		echo json_encode($da);
	}
	//删除
	 function delguige(){
		//获取请求参数
		$id = $_GET['id'];
		if(!empty($id)){
			M('mv_class')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','分类删除');
			 $da['msg']="删除成功";
			  $da['href']=U("fenlei/alist");
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("fenlei/alist");
		}
		echo json_encode($da);
	}
	
}
