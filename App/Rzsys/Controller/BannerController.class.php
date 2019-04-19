<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class BannerController extends AllowController
{
    //加载后台模板
    public function alist()
	{   
		$User = M('mv_banner'); // 实例化User对象
		$where='IS_DEL=0';
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
		
		
		$this->assign('ad',$list);// 赋值数据集
		$count      = $User->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('pg',$p);
		//种类
		$class=M('mv_class')->where('IS_DEL=0')->select();
		$newclass=[];
		if(!empty($class)){
			foreach($class as $key=>$val){
				$newclass[$val['ID']]=$val['CL_NAME'];
			}
		}
		$this->assign('type',$newclass);

		$this->display(); // 输出模板 
    }
	//管理员添加
	public function add()
	{   
		//获取请求参数
		$id = $_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mv_banner')->find($id);
		}
		$data['class']=M('mv_class')->where('IS_DEL=0 ')->select();
		$data['pg']=$_GET['pg'];
		
      $this->assign('data',$data);
	
		$this->display(); // 输出模板
    }
	//管理员提交
	public function doadd(){
	
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['BN_NAME']=$_POST['name'];
		$data['BN_URL']=$_POST['spic'];
		$data['BN_TYPE']=$_POST['type'];
		$data['BN_TYPE_ID']=$_POST['typeid'];
	
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mv_banner')->where("ID=$id")->save($data);
			$this->addlog('2','轮播图编辑');
			$da['msg']="修改成功";
			$da['href']=U("banner/alist")."?p=$pg";
			
		}else{
				$this->addlog('1','轮播图添加');
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mv_banner')->add($data);
				$da['msg']="添加成功";
				$da['href']=U("banner/alist")."?p=$pg";
		}
		echo json_encode($da);
		
		 
	}
	//删除
	public function del(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			$this->addlog('3','轮播图删除');
			M('mv_banner')->where("ID=$id")->save(['IS_DEL'=>1]);
			   $da['msg']="删除成功";
			 $da['href']=U("banner/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("banner/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	
	 

}
