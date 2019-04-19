<?php
namespace Rzsys\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function login(){
		$this->display();
    }
	
	public function dologin(){
		$username = I('post.username');
		$password = md5(I('post.password'));
		$verify = I('post.verify');
		
		if(!$this->check_verify($verify)) {
			$data['status'] = 0;
			$data['msg'] = "验证码输入错误";
			$this->ajaxReturn($data);
		}
		$admin = M("mage_admin_user")->where("AU_ACCOUNT = '".$username."' and IS_DEL=0")->find();
		//echo M("mage_admin_user")->getlastsql();die();
		if($admin){
			//echo $password;die();
			$password = ($password);
			$result = M('mage_admin_user')->where("AU_ACCOUNT='".$username."' and AU_PSWD='".$password."' and IS_DEL=0")->find(); //查询判断账号和密码是否正确
			//echo  M('mage_admin_user')->getlastsql();die();
			if($result){
				/*if($result['state'] == 1){
					$data['status'] = 0;
					$data['msg'] = "对不起，你的账号已封号";
					$this->ajaxReturn($data);
				}*/
				$_SESSION['adminid'] = $result['ID'];  //保存用户ID到SESSION
				$_SESSION['adminuser'] = $result['AU_ACCOUNT'];  //保存用户名到SESSION
				$_SESSION['power'] = $result['AU_PERMISSION'];  //保存用户名到SESSION
				//$_SESSION['jid'] = $result['jid'];  //保存权限到SESSION
				$data['status'] = 1;
				$data['msg'] = "登录成功！";
				$this->addlog('0','登录');
				$this->ajaxReturn($data);
			}else{
				$data['status'] = 0;
				$data['msg'] = "账号或者密码错误，请重新登陆";
				$this->ajaxReturn($data);
			}
		}else{
			$data['status'] = 0;
			$data['msg'] = "该帐号不存在";
			$this->ajaxReturn($data);
		}
    }
	
	//登陆注销
	public function logout(){
		$this->addlog('5','退出登录');
    	session('adminid',null); //清空全部session
    	session('adminuser',null); //清空全部session
    	session('adminqid',null); //清空全部session
		
    	$this->redirect('admin.php/Login/login');
    }
	//验证码
	function check_verify($code, $id = ''){
		$verify = new \Think\Verify();
		return $verify->check($code, $id);
	}
	//验证码
	Public function verify(){
		$config = array(    
		'fontSize'    =>    18,    // 验证码字体大小    
		'length'      =>    5,     // 验证码位数
		'imageW'      =>    150,  
		'imageH'      =>    42,	
		'reset' => false,	
		);
		ob_clean();
		$Verify = new \Think\Verify($config);
		$Verify->entry();
	}
	
	 public function upload(){
        $path = "Public/upload/".date("Y-m-d")."/";
		if(!is_file($path)){
			mkdir($path,0777);
		}
		$extArr = array("jpg", "png", "gif" , "jpeg");
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
			$name = $_FILES['pic']['name'];
			$size = $_FILES['pic']['size'];
			
			if(empty($name)){
				echo '请选择要上传的图片';
				exit;
			}
			$extend = pathinfo($name);
			$ext = strtolower($extend["extension"]);
			
			if(!in_array($ext,$extArr)){
				echo '图片格式错误！';
				exit;
			}
			
			$image_name = date("Y_m_d_H_i_s")."_".rand(10000,99999).".".$ext;
			$tmp = $_FILES['pic']['tmp_name'];
			$pathn=ltrim($path,'.');
			$fileurl = $pathn.$image_name;
			
			if(@move_uploaded_file($tmp, $path.$image_name)){
				$data['code']=0;
				$data['msg']="上传成功";
				$data['img']=$path.$image_name;
				echo json_encode($data);
			}else{
				echo '上传出错了！';
			}
			exit;
		}
		exit;
    }
	public function uploads(){
        $path = "Public/upload/".date("Y-m-d")."/";
		if(!is_file($path)){
			mkdir($path,0777);
		}
		$extArr = array("jpg", "png", "gif" , "jpeg");
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
			$name = $_FILES['source']['name'];
			$size = $_FILES['source']['size'];
			
			if(empty($name)){
				echo '请选择要上传的图片';
				exit;
			}
			$extend = pathinfo($name);
			$ext = strtolower($extend["extension"]);
			
			if(!in_array($ext,$extArr)){
				echo '图片格式错误！';
				exit;
			}
			
			$image_name = date("Y_m_d_H_i_s")."_".rand(10000,99999).".".$ext;
			$tmp = $_FILES['source']['tmp_name'];
			$pathn=ltrim($path,'.');
			$fileurl = $pathn.$image_name;
			
			if(@move_uploaded_file($tmp, $path.$image_name)){
				$data['code']=0;
				$data['msg']="上传成功";
				$data['img']=$path.$image_name;
				echo json_encode($data);
			}else{
				echo '上传出错了！';
			}
			exit;
		}
		exit;
    }
	public function addlog($type,$desc){
		$data['LOG_TYPE']=$type;
		$data['LOG_UID']=$_SESSION['adminid'];
		$data['LOG_NAME']=$_SESSION['adminuser']; 
		$data['LOG_DESC']=$desc;
		$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
		M('mage_log')->add($data);
	}
	//上传图集
	public function uploadall(){
		
		
		$targetFolder = 'Public/upload/goods'; // Relative to the root

		//$verifyToken = md5('unique_salt' . $_POST['timestamp']);
		
		if (!empty($_FILES) ) {
			//var_dump($_FILES);die();
			$tempFile = $_FILES['file']['tmp_name'];
			$targetPath = $_SERVER['DOCUMENT_ROOT'].'/'. $targetFolder;
			
			$fileTypes = array('wav','mp3','wma','mod','ra','cd','md','asf','jpg','jpeg','gif','png','flv','avi','mp4','3gp','f4v','mov','rmvb','wmv'); // File extensions
			$fileParts = pathinfo($_FILES['file']['name']);
			$str =time().(microtime()*10000000).rand(999,1999);
			$targetFile = rtrim($targetPath,'/') . '/' .$str.'.'.$fileParts['extension'];
			$filepath= rtrim($targetFolder,'/') . '/' .$str.'.'.$fileParts['extension'];
			$filepathvideo=rtrim($targetFolder,'/') . '/' .$str.'.flv';
		
			$fileParts['extension'] = strtolower($fileParts['extension']);
			if (in_array($fileParts['extension'],$fileTypes)) {
				move_uploaded_file($tempFile,$targetFile);
				echo $filepath;
				exit();
			} else {
				echo '上传失败';
				exit();
			}
		}
		
	}
}