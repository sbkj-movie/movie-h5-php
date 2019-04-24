<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class PayController extends AllowController
{
    //加载后台模板
     function alist()
	{   
		$User = M('mv_shop_history'); // 实例化User对象
		$where='IS_DEL=0';
		$date=$_GET['date'];
		$uid=$_GET['uid'];
		$date1=$_GET['date1'];
		$pay=$_GET['pay'];
		if(!empty($date)){
			$d=date('Y-m-d',strtotime($date));
			$where.=" and GMT_CREATE>'$d'";
		}
		
		if(!empty($pay)){
			if($pay==2){
				$pay=0;
			}
			$where.=" and IS_PAY=$pay";
		}
		if(!empty($date1)){
			$ds=date('Y-m-d',strtotime($date1));
			
			$where.=" and GMT_CREATE < '$ds' ";
		}
		if(!empty($uid)){
		
			$where.=" and SH_USERID=$uid";
		}
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
		//echo $User->getlastsql();die();
		if(!empty($list)){
			foreach($list as $key=>$val){
					$user=M('mv_user')->find($val['SH_USERID']);
					$list[$key]['SH_USERNAME']=$user['USERNAME'];
				
			}
		}				
	   //echo $User->getlastsql();
	
		$this->assign('ad',$list);// 赋值数据集
		//支付总金额
		$paymoney = $User->where($where.' and IS_PAY=1')->order('ID desc')->sum('SH_PAY');
		
		$this->assign('paymoney',$paymoney);
		
	
		$count      = $User->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('getall',$_GET);
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
		$ispay=['0'=>'未支付','1'=>'支付'];
		$this->assign('ispay',$ispay);
		$this->display(); // 输出模板
	}
	 function tixian()
	{   
		$User = M('mv_put_forward'); // 实例化User对象
		$where='IS_DEL=0';
		$date=$_GET['date'];
		$uid=$_GET['uid'];
		$type=$_GET['type'];
		if(!empty($type)){
			$where.=' and PF_STATUS='.$type;
		}
		if(!empty($date)){
			$d=date('Y-m-d',strtotime($date));
			$where.=" and GMT_CREATE>$d";
		}
		if(!empty($uid)){
		
			$where.=" and PF_USER_ID=$uid";
		}
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
		if(!empty($list)){
			foreach($list as $key=>$val){
					$user=M('mv_user')->find($val['PF_USER_ID']);
					$list[$key]['SH_USERNAME']=$user['USERNAME'];
					//银行
					$bank=M('mv_bank_card')->find($val['PF_BANK_CARD_ID']);
					$list[$key]['BC_NUMBER']=$bank['BC_NUMBER'];//卡号
					$list[$key]['BC_NAME']=$bank['BC_NAME'];//持卡人姓名
					$list[$key]['BC_TELNO']=$bank['BC_TELNO'];//持卡人手机号
					$list[$key]['BC_OPEN_BANK']=$bank['BC_OPEN_BANK'];//开户行
					$list[$key]['BC_OPEN_BANK_ADDRESS']=$bank['BC_OPEN_BANK_ADDRESS'];//开户行地址
				
			}
		}				
	   //echo $User->getlastsql();
	
		$this->assign('ad',$list);// 赋值数据集
	
		$count      = $User->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
		$state=['0'=>'未审核','1'=>'通过','2'=>'拒绝'];
		$this->assign('state',$state);
		$this->display(); // 输出模板
	}
	 function tongguo(){
		//获取请求参数
		$id = $_GET['id'];
		$state = $_GET['state'];
		$pg=$_GET['pg'];
		$desc=$_GET['desc'];
		
		if(!empty($id)){
			
			if($state==2){
				$tixian=M('mv_put_forward')->find($id);
				$user=M('mv_user')->find($tixian['PF_USER_ID']);
				//修改用户金额
				$money=$user['NO_PRICE']+$tixian['PF_PRICE'];
				$fmoney=$user['IS_PRICE']-$tixian['PF_PRICE'];
				M('mv_user')->where("ID=$user[ID];")->save(['NO_PRICE'=>$money,'IS_PRICE'=>$fmoney]);
			}
			M('mv_put_forward')->where("ID=$id")->save(['PF_STATUS'=>$state,'PF_COMMENT'=>$desc]);
			   $da['msg']="成功";
			  $da['href']=U("Pay/tixian")."?p=$pg";
		}else{
			 $da['msg']="失败";
			$da['href']=U("Pay/tixian")."?p=$pg";
		}
		echo json_encode($da);
	}
	//虎皮椒账号列表
	function  hplist(){
		$User = M('mage_hupijiao'); // 实例化User对象
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
		 $this->assign('pg',$p);
		
		$this->display(); // 输出模板
	}
	public function hpadd()
	{   
		//获取请求参数
		$id = $_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mage_hupijiao')->find($id);
		}
		$data['pg']=$_GET['pg'];
		
       $this->assign('data',$data);
	
		$this->display(); // 输出模板
    }
	//管理员提交
	public function hpdoadd(){
	
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
        $data['HU_NAME']=$_POST['name'];
		$data['HU_APPID']=$_POST['appid'];
		$data['HU_TYPE']=$_POST['type'];
		$data['HU_APP_SECRET']=$_POST['appsecret'];
		$data['HU_APP_GONG']=$_POST['appgong'];
		$data['MAX_MONEY']=$_POST['money'];
        $data['SORT_NUM']=$_POST['sortnum'];
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mage_hupijiao')->where("ID=$id")->save($data);
			$da['msg']="修改成功";
			$this->addlog('2','支付账号修改');
			$da['href']=U("pay/hplist")."?p=$pg";
			
		}else{
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mage_hupijiao')->add($data);
				$this->addlog('1','支付账号添加');
				$da['msg']="添加成功";
				$da['href']=U("pay/hplist")."?p=$pg";
		}
		echo json_encode($da);
		
		 
	}
	public function hpdel(){
		//获取请求参数
		$id = $_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			M('mage_hupijiao')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','支付账号删除');
			 $da['msg']="删除成功";
			 $da['href']=U("pay/hplist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("pay/hplist")."?p=$pg";
		}
		echo json_encode($da);
	}
}
