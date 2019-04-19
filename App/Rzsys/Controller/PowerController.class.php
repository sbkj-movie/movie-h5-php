<?php
namespace Rzsys\Controller;
use Think\Controller;

class PowerController extends AllowController
{
    //加载后台模板
    public function info()
	{   
		$User = M('mage_admin_user'); // 实例化User对象
		$where='IS_DEL=0';
		
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
	//echo $User->getlastsql();
		$this->assign('ad',$list);// 赋值数据集
	
		$count      = $User->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		
		$this->assign('page',$show);// 赋值分页输出
		//角色
		 $juese=M('juese')->select();
		 
		 $power=[];
		 foreach($juese as $val){	
				$power[$val['id']]=$val['name'];
		 }
		 $this->assign('power',$power);
		 $state=['0'=>'正常','1'=>'封号'];
		 $this->assign('state',$state);
		  $this->assign('adminuid',$_SESSION['adminid']);
		 $this->assign('pg',$p);
		$this->display(); // 输出模板
    }
	//管理员添加
	public function add()
	{   
		//获取请求参数
		$id =$_GET['id'];
		$data=array();
		$data['id']=$id;
		if(isset($id)&&$id!=0){
			$data=M('mage_admin_user')->find($id);
			$data['AU_PERMISSION']=explode(',',$data['AU_PERMISSION']);
		}
		
		//角色
		//$data['juese']=M('juese')->select();
		$data['pg']=$_GET['pg'];
		//var_dump($data);
        $this->assign('data',$data);
		$this->display(); // 输出模板
    }
	//修改账户密码
	public function pwdedit(){
		//获取请求参数
		$id =$_SESSION['adminid'];
		$data=array();
		$data['id']=$id;
		if(isset($id)&&$id!=0){
			$data=M('mage_admin_user')->find($id);
			$data['AU_PERMISSION']=explode(',',$data['AU_PERMISSION']);
		}
		
		//角色
		//$data['juese']=M('juese')->select();
		$data['pg']=$_GET['pg'];
		//var_dump($data);
        $this->assign('data',$data);
		$this->display(); // 输出模板
	}
	//管理员提交
	public function dopwdedit(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$password=$_POST['pwd'];
		$ypwd=md5($_POST['ypwd']);
		//secho $password;die();
	    //判断用户名是否已被使用
		$admin = M("mage_admin_user")->where("AU_PSWD = '".$ypwd."' and ID=$id")->find();
		//echo M("mage_admin_user")->getlastsql();die();
		if(empty($admin)){
			$da['code']=1;
			  $da['msg']="原密码错误";
			 //$da['href']=U("power/info")."?p=$pg";
			echo json_encode($da);exit();
		}
		$da['code']=0;
		
			if($password!=''){
				$data['AU_PSWD']=md5($password);
			}
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mage_admin_user')->where("ID=$id")->save($data);
			$this->addlog('2','管理员修改');
			  $da['msg']="修改成功";
			 $da['href']=U("power/info")."?p=$pg";
			echo json_encode($da);
		
		 
	}
	//管理员提交
	public function doadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['AU_ACCOUNT']=$_POST['name'];
		$data['AU_PERMISSION']=substr($_POST['arr'],0,-1);
		$password=$_POST['pwd'];
		//secho $password;die();
	    //判断用户名是否已被使用
		$da['code']=0;
		
		if(isset($id)&&$id!=0){
			if($password!=''){
				$data['AU_PSWD']=md5($password);
			}
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mage_admin_user')->where("ID=$id")->save($data);
			$this->addlog('2','管理员修改');
			  $da['msg']="修改成功";
			 $da['href']=U("power/info")."?p=$pg";
		}else{
			$ad=M('mage_admin_user')->where("AU_ACCOUNT='$data[AU_ACCOUNT]' and IS_DEL=0")->find();
			if(!empty($ad)){
				$da['code']=1;
				$da['msg']="用户名已存在";
				$da['href']='';
				echo json_encode($da);
				exit();
			}
				$data['AU_PSWD']=md5($password);
				
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_admin_user')->add($data);
				$this->addlog('1','管理员添加');
				$da['msg']="添加成功";
			 $da['href']=U("power/info")."?p=$pg";
		
		}
			echo json_encode($da);
		
		 
	}
	//删除
	public function del(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			M('mage_admin_user')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','管理员删除');
			   $da['msg']="删除成功";
			  $da['href']=U("power/info")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("power/info")."?p=$pg";
		}
		echo json_encode($da);
	}
	//封号
	public function fenghao(){
		//获取请求参数
		$id = $_GET['id'];
		$state = $_GET['state'];
		$pg=$_GET['pg'];
		if($state==0){
			$state=1;
		}else{
			if($state==1){
				$state=0;
			}
		}
		if(!empty($id)){
			M('mage_admin_user')->where("id=$id")->save(['state'=>$state]);
			 $da['msg']="成功";
			  $da['href']=U("power/info")."?p=$pg";
		}else{
			 $da['msg']="失败";
			$da['href']=U("power/info")."?p=$pg";
		}
		echo json_encode($da);
	}

	//新增权限
	public function  power(){
		//获取请求参数
		$id = $_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('juese')->find($id);
		}
		
		$data['pg']=$_GET['pg'];
       $this->assign('data',$data);
		$this->display(); // 输出模板
	}
	//删除quanxian
	public function pwdel(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			M('juese')->delete($id);
			M('rel')->where("pid=$id")->delete();
			   $this->success('删除成功', U("power/pwlist")."?p=$pg");
			}else{
				$this->error('删除失败', U("power/pwlist")."?p=$pg");
		}
	}
	//权限管理
	public function pwlist(){
		$User = M('juese'); // 实例化User对象
		
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
	
		$list = $User->page("$p,25")->select();
	//echo $User->getlastsql();
		$this->assign('ad',$list);// 赋值数据集
	
		$count      = $User->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		
		$this->assign('page',$show);// 赋值分页输出
	
		 $this->assign('ad',$list);
		 $this->assign('pg',$p);
		$this->display(); // 输出模板
	}
	//权限添加
	public function  pdoadd(){
	
		$rid = $_POST['rid'];
		$name = $_POST['name'];
		$oCks = $_POST['oCks'];
		$oCks=explode(',',$oCks);
		if(empty($rid)){
			$data['name']=$name;
			$data['addtime']=time();
			M('juese')->add($data);
			$rid = M('juese')->getLastInsID();
			if(!empty($rid)){
				foreach($oCks as $value){
					if(!empty($value)){
						$jie=[];
						$jie['jid']=$value;
						$jie['pid']=$rid;
						M('rel')->add($jie);
					}	
				}
			}
			echo json_encode(1);exit();
		}else{
			M('juese')->where("id=$rid")->save(['name'=>$name]);
			M('rel')->where("pid=$rid")->delete();
			if(!empty($rid)){
				foreach($oCks as $value){
					if(!empty($value)){
						$jie=[];
						$jie['jid']=$value;
						$jie['pid']=$rid;
						M('rel')->add($jie);
					}	
				}
			}
			echo json_encode(1);exit();
		}
	}
	public function listrole(){
		$rid = $_POST['rid'];
		$data=[];
		
			$qx=M('pro')->where('pid=0')->select();
			$idall=[];
			if(!empty($rid)){
			
				$allid=M('rel')->where("pid=$rid")->select();
				
				if(!empty($allid)){
					foreach($allid as $val){
						$idall[]=$val['jid'];
					}
				}
			}
			
			foreach($qx as $val){
				$da=[];
				$pid=$val['id'];
				$son=M('pro')->where("pid=$pid")->order('id asc')->select();
				$da['title']=$val['name'];
				$da['value']=$val['id'];
				if(in_array($val['id'],$idall)){
					$da['checked']=true;
				}
				if(empty($son)){
					
				}else{
					foreach($son as $vals){
						$s=[];
						$s['title']=$vals['name'];
						$s['value']=$vals['id'];
						$s['data']=[];
						if(in_array($vals['id'],$idall)){
							$s['checked']=true;
						}
						$da['data'][]=$s;
					}
				}
				$data[]=$da;
			}
		
		echo json_encode($data);exit();
	}
}
