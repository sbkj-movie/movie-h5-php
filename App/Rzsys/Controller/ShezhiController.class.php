<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class ShezhiController extends AllowController
{
   //客服管理
   public function kefu(){
   		$User = M('mage_custom_service'); // 实例化User对象
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
		
		$this->display(); // 输出模板
   }
   //添加客服
     public function kefuadd(){
   		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mage_custom_service')->find($id);
			
		}
		$data['pg']=$_GET['pg'];
	
      $this->assign('data',$data);
		$this->display(); // 输出模板
   }
    function kefudoadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['CS_TYPE']=$_POST['type'];
		$data['CS_NUMBER']=$_POST['hao'];
		//var_dump($picall);die();
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mage_custom_service')->where("ID=$id")->save($data);
			$this->addlog('2','客服修改');
			$da['msg']="修改成功";
			$da['href']=U("shezhi/kefu")."?p=$pg";
		}else{
				
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_custom_service')->add($data);
				$this->addlog('1','客服添加');
				$da['msg']="添加成功";
				$da['href']=U("shezhi/kefu")."?p=$pg";
		}
		
		echo json_encode($da);
		 
	}
	//删除
	 function kefudel(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			
			M('mage_custom_service')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','客服删除');
			 $da['msg']="删除成功";
			  $da['href']=U("shezhi/kefu")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("shezhi/kefu")."?p=$pg";
		}
		echo json_encode($da);
	}
	//版本管理
   public function banben(){
   		$User = M('mage_app'); // 实例化User对象
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
		$state=['1'=>'苹果','2'=>'安卓'];
		$this->assign('state',$state);
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
		
		$this->display(); // 输出模板
   }
//添加版本
     public function banbenadd(){
   		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mage_app')->find($id);
		}
		$data['pg']=$_GET['pg'];
	
      $this->assign('data',$data);
		$this->display(); // 输出模板
   }
   function banbendoadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['APP_TYPE']=$_POST['type'];
		$data['APP_NUM']=$_POST['banbun'];
		$data['APP_HREF']=$_POST['href'];
		//var_dump($picall);die();
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mage_custom_service')->where("ID=$id")->save($data);
			$this->addlog('2','版本修改');
			$da['msg']="修改成功";
			$da['href']=U("shezhi/kefu")."?p=$pg";
		}else{
				
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_app')->add($data);
				$this->addlog('1','版本添加');
				$da['msg']="添加成功";
				$da['href']=U("shezhi/banben")."?p=$pg";
		}
		
		echo json_encode($da);
		 
	}
	//删除版本
	 function banbendel(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			
			M('mage_app')->where("ID=$id")->save(['IS_DEL'=>1]);
			 $da['msg']="删除成功";
			 $this->addlog('3','版本删除');
			  $da['href']=U("shezhi/banben")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("shezhi/banben")."?p=$pg";
		}
		echo json_encode($da);
	}
    //关于我们
    public function article()
	{   
		$User = M('mv_setall'); // 实例化User对象
		$list = $User->where("TYPE='about'")->find();
		$this->assign('data',$list);// 赋值数据集
		
		$this->display(); // 输出模板 
    }
	//推广说明
    public function tuiguang()
	{   
		$User = M('mv_setall'); // 实例化User对象
		$list = $User->where("TYPE='tuiguang'")->find();
		$this->assign('data',$list);// 赋值数据集
		
		$this->display(); // 输出模板 
    }
	 //引导文字
    public function yindao()
	{   
		$User = M('mv_setall'); // 实例化User对象
		
		$list = $User->where("TYPE='yindao'")->find();
		
		
		$this->assign('data',$list);// 赋值数据集
		
		$this->display(); // 输出模板 
    }
	//管理员提交
	public function doadd(){
	
		//获取请求参数
		$type=$_POST['type'];
		
		$data['CONTENT']=$_POST['content'];
		//$data['addtime']=time();

		
			M('mv_setall')->where("TYPE='{$type}'")->save($data);
			$this->addlog('2','系统内容修改');
			$da['msg']="修改成功";
			$da['href']=U("shezhi/{$type}");
			
		
		echo json_encode($da);
		
		 
	}
	 public function system()
	{   
		$User = M('mv_setall'); // 实例化User对象
		
		$bili_one = $User->where("TYPE='bili_one'")->find();
		$this->assign('bili_one',$bili_one);
		
		$bili_two = $User->where("TYPE='bili_two'")->find();
		$this->assign('bili_two',$bili_two);
		
		//$bili_three = $User->where("TYPE='bili_three'")->find();
		//$this->assign('bili_three',$bili_three);
		
		$this->display(); // 输出模板 
    }
	public function dosysadd(){
	
		//获取请求参数
		
		$bili_one['VALUE']=$_POST['bili_one'];
		$bili_two['VALUE']=$_POST['bili_two'];
		//$bili_three['VALUE']=$_POST['bili_three'];
		M('mv_setall')->where("TYPE='bili_one'")->save($bili_one);
		M('mv_setall')->where("TYPE='bili_two'")->save($bili_two);
		//M('mv_setall')->where("TYPE='bili_three'")->save($bili_three);
		$this->addlog('2','系统参数修改');
			$da['msg']="修改成功";
			$da['href']=U("shezhi/system");
			
		
		echo json_encode($da);
		
		 
	}
	//日志列表
	 public function loglist(){
   		$User = M('mage_log'); // 实例化User对象
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
		$state=['0'=>'登录','1'=>'添加','2'=>'修改','3'=>'删除','4'=>'状态','5'=>'退出登录'];
		$this->assign('state',$state);
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
		
		$this->display(); // 输出模板
   }
//删除
	 function logdel(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			
			M('mage_log')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','日志删除');
			 $da['msg']="删除成功";
			  $da['href']=U("shezhi/loglist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("shezhi/loglist")."?p=$pg";
		}
		echo json_encode($da);
	}
}
