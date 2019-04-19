<?php 
namespace Rzsys\Controller;
use Think\Controller;
class AllowController extends Controller
{
	public function _initialize(){
		
		if(empty($_SESSION['adminid']) or !isset($_SESSION['adminid'])){
			//echo 111;die();
			//$this->error("无访问权限--请先登录",U("Login/login"));
			
			$this->redirect('admin.php/Login/login');
		}else{
			//判断是否有权限登录
			//$user=Session::get('admin');
			$power=explode(',',$_SESSION['power']);
			
			$c=CONTROLLER_NAME;
				$a=ACTION_NAME;
				$c=strtolower($c);
				$a=strtolower($a);
				
			if(strstr($a,'add')){
				if(!in_array('edit',$power)){
					$this->error("无访问权限","");
				}
					
			}
			
			if(strstr($a,'del')){
				
				if(!in_array('del',$power)){
					$da['msg']="无访问权限";
					$da['href']=U("power/info")."?p=$pg";
					echo json_encode($da);exit();
					//$this->error("无访问权限","/admin.php/login/login");
				}
			}
			
				
		}
	}
	public function addlog($type,$desc){
		$data['LOG_TYPE']=$type;
		$data['LOG_UID']=$_SESSION['adminid'];
		$data['LOG_NAME']=$_SESSION['adminuser']; 
		$data['LOG_DESC']=$desc;
		$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
		M('mage_log')->add($data);
	}
}

 ?>
