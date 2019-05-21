<?php
// 本类由系统自动生成，仅供测试用途
namespace Rzsys\Controller;
use Think\CommController\Controller;
class UserController extends AllowController {
   //用户列表
   function alist(){
		$User = D("mv_user"); // 实例化User对象
		$where='IS_DEL=0';
		$name=$_GET['name'];
		$uid=$_GET['uid'];
		$retime1=$_GET['retime1'];
		$retime2=$_GET['retime2'];
		$stype=$_GET['stype'];
		if(!empty($name)){
			$where.=" and USERNAME like '%{$name}%'";
		}
		if(!empty($uid)){
			$where.=" and ID=$uid ";
		}
		$wheres='1';
		if($stype==1){
			if(!empty($retime1)){
				$d=date('Y-m-d',strtotime($retime1));
				//$end=date('Y-m-d',(strtotime($d)+24*3600));
				$where.=" and GMT_CREATE > '$d' ";
			}
			if(!empty($retime2)){
				$ds=date('Y-m-d',strtotime($retime2));
				//$end=date('Y-m-d',(strtotime($d)+24*3600));
				$where.=" and GMT_CREATE < '$ds' ";
			}	
		}else{
			
			if($stype==2){
				if(!empty($retime1)){
					$ut=date('Y-m-d',strtotime($retime1));
					$wheres.=" and GMT_CREATE > '$ut' ";
				}
				if(!empty($retime2)){
					$uts=date('Y-m-d',strtotime($retime2));
					$wheres.=" and GMT_CREATE < '$uts' ";
				}
			}
			if($stype==3){
				if(!empty($retime1)){
					$nt=date('Y-m-d',strtotime($retime1));
					$wheres.=" and SH_END > '$nt' ";
				}
				if(!empty($retime2)){
					$nts=date('Y-m-d',strtotime($retime2));
					$wheres.=" and SH_END < '$nts' ";
				}
			}
			
			
		}
		
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
		
	
		
		$this->assign('getall',$_GET);
		
		if($wheres!='1'){
			
			$wheres.=' and IS_DEL=0';
			$wheres.=' and IS_PAY=1';
			//echo $wheres;die();
			$list =M('mv_shop_history')->field('SH_USERID,SH_END as GMT_ENDTIME,GMT_CREATE as viptime')->where($wheres)->order('ID desc')->page("$p,25")->select();
			//echo M('mv_shop_history')->getlastsql();die();
			if(!empty($list)){
				foreach($list as $key=>$val){
					//套餐id
					$user= $User->find($val['SH_USERID']);
					
					if(!empty($user)){
						foreach($user as $k=>$v){
							$list[$key][$k]=$v;
						}
					}
				}
			}
			
		//echo $User->getlastsql();
			$this->assign('ad',$list);// 赋值数据集
		
			$count      = M('mv_shop_history')->where($wheres)->count();// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
			$show       = $Page->show();// 分页显示输出
			
			$this->assign('page',$show);// 赋值分页输出
			 $this->assign('pg',$p);
			
			 //$state=['1'=>'使用中','0'=>'已封号'];
			//$this->assign('state',$state);
			$this->display(); // 输出模板 
		}else{
			
			$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
			//echo $User->getlastsql();die();
			if(!empty($list)){
				foreach($list as $key=>$val){
					//$bank=M('mv_bank_card')->where("BC_USERID=$val[ID] and IS_DEL=0")->find();
					//if(!empty($bank)){
						//$list[$key]['BC_OPEN_BANK']=$bank['BC_OPEN_BANK'];
						//$list[$key]['BC_NUMBER']=$bank['BC_NUMBER'];
						//$list[$key]['BC_OPEN_BANK_ADDRESS']=$bank['BC_OPEN_BANK_ADDRESS'];
					//}
					if(!empty($val['PARENT_CODE'])){
						$shangji=$User->where("EXTENSION_CODE='{$val[PARENT_CODE]}'")->find();
						if(!empty($shangji)){
							$list[$key]['TOPUSER']=$shangji['USERNAME'];
						}
					}
					
					//套餐id
					$taocan=M('mv_shop_history')->where("SH_USERID=$val[ID] and IS_PAY=1")->order('ID desc')->find();
					
					if(!empty($taocan)){
						$list[$key]['GMT_ENDTIME']=$taocan['SH_END'];
						$list[$key]['viptime']=$taocan['GMT_CREATE'];
					}
				}
			}
			
		//echo $User->getlastsql();
			$this->assign('ad',$list);// 赋值数据集
		
			$count      = $User->where($where)->count();// 查询满足要求的总记录数
			$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
			$show       = $Page->show();// 分页显示输出
			
			$this->assign('page',$show);// 赋值分页输出
			 $this->assign('pg',$p);
			
			 //$state=['1'=>'使用中','0'=>'已封号'];
			//$this->assign('state',$state);
			$this->display(); // 输出模板 
		}
		
		
   }
	public function add()
	{   
		//获取请求参数
		$id = $_GET['id'];
		$data=array();
		$data['ques']='';
		if(isset($id)&&$id!=0){
            $data = M('mv_user')->find($id);
            //有效套餐id
            $shid = M('mv_shop_history')->where("SH_USERID=$id and IS_PAY=1 and SH_END > '".date('Y-m-d h:i:s', time())."'")->order('ID desc')->find();

            if (!empty($shid)) {
                $data['SHID'] = $shid['SHID'];
            }
			if(!empty($data['SECURITY_QUESTION'])){
				$que=json_decode($data['SECURITY_QUESTION']);
				$ques=[];
				foreach($que as $key=>$val){
					$ques[$key]['question']=$val->question;
					$ques[$key]['answer']=$val->answer;
				}
				$data['ques']=$ques;
			}
			
		}
		
		//套餐
		$taocan=M('mage_shop')->select();
		//var_dump($taocan);die();
		$data['taocan']=$taocan;
		
		$data['id']=$id;
		$data['pg']=$_GET['pg'];
		
        $this->assign('data',$data);
		$this->display(); // 输出模板 
    }
	//用户提交
	public function doadd(){
	
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['USERNAME']=$_POST['name'];
		if(!empty($_POST['password'])){
			$data['LOGIN_PSWD']=md5($_POST['password']);
		}
		$data['FACTION']=$_POST['fenxiao'];
		$SPID=$_POST['tid'];
		//$data['NO_PRICE']=$_POST['money'];
		$ques=[];
		$ques[1]['question']=$_POST['question1'];
		$ques[1]['answer']=$_POST['answer1'];
//		$ques[2]['question']=$_POST['question2'];
//		$ques[2]['answer']=$_POST['answer2'];
//		$ques[3]['question']=$_POST['question3'];
//		$ques[3]['answer']=$_POST['answer3'];
		$data['SECURITY_QUESTION']=json_encode($ques);
		$data['PARENT_CODE']=$_POST['pid'];
		$is_vip = $_POST['is_vip'];
		
		
		if($data['PARENT_CODE']==0){
			$data['PARENT_CODE']='';
		}
		if(!empty($data['PARENT_CODE'])){
			$shangji=M('mv_user')->where("PARENT_CODE={$data['PARENT_CODE']}")->find();
			if(empty($shangji)){
				$da['msg']="上级不存在";
				$da['href']='';
				echo json_encode($da);exit();
			}
			
		}
		if($SPID!=0){
			$taocan=M('mage_shop')->find($SPID);
			$endtime=date('Y-m-d H:i:s',(time()+$taocan['SP_END_TIME']*24*3600));
			
		}
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFIED']=date('Y-m-d H:i:s',time());
			M('mv_user')->where("ID=$id")->save($data);
			//修改购买记录
			//添加套餐购买记录
			$user=M('mv_user')->find($id);
			if($SPID!=0&&$is_vip==0){
				$tc_buy=[];
				$tc_buy['SH_USERID']=$id;
				$tc_buy['SHID']=$SPID;
				$tc_buy['SH_NAME']=$taocan['SP_NAME'];
				$tc_buy['SH_PRICE']=$taocan['SP_PRICE'];
				$tc_buy['SH_PAY']=0;
				$tc_buy['SH_END']=$endtime;
				$tc_buy['IS_PAY']=1;
				$tc_buy['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mv_shop_history')->add($tc_buy);
			}
			
			
			
			//M('mv_user')->add($data);
			$this->addlog('2','用户修改');
			$da['msg']="修改成功";
			$da['href']=U("user/alist")."?p=$pg";
			
		}else{
				$data['USER_TYPE']=1;
				$data['USERIMG']='/Public/admin/images/head/'.rand(1,12).'.png';
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				//推广码
				$str='';
				for($i=0;$i<6;$i++){
					$str.=chr(rand(65,90));
				}
				
				M('mv_user')->add($data);
				$id= M('mv_user')->getLastInsID();
                if($SPID!=0&&$is_vip==0){
					$tc_buy=[];
					$tc_buy['SH_USERID']=$id;
					$tc_buy['SHID']=$SPID;
					$tc_buy['SH_NAME']=$taocan['SP_NAME'];
                    $tc_buy['SH_PRICE']=$taocan['SP_PRICE'];
					$tc_buy['SH_PAY']=0;
					$tc_buy['SH_END']=$endtime;
					$tc_buy['IS_PAY']=1;
					$tc_buy['GMT_CREATE']=date('Y-m-d H:i:s',time());
					
					M('mv_shop_history')->add($tc_buy);
				}
				$up['EXTENSION_CODE']='ID'.$id.$str;
				M('mv_user')->where("ID=$id")->save($up);
				$this->addlog('1','用户添加');
				$da['msg']="添加成功";
				$da['href']=U("user/alist")."?p=$pg";
		}
		echo json_encode($da);
		
		 
	}
	//停用
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
			M('mv_user')->where("ID=$id")->save(['STATE'=>$state]);
			if($state==0){
				$this->addlog('4','用户停用');
			}else{
				if($state==1){
				$this->addlog('4','用户启用');
				}
			}
			
			   $da['msg']="成功";
			  $da['href']=U("user/alist")."?p=$pg";
		}else{
			 $da['msg']="失败";
			$da['href']=U("user/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	//删除
	public function del(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			M('mv_user')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','用户删除');
			   $da['msg']="删除成功";
			 $da['href']=U("user/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("user/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	
}