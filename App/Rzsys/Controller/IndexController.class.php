<?php
// 本类由系统自动生成，仅供测试用途
namespace Rzsys\Controller;
use Think\Controller;
class IndexController extends AllowController {
	
    public function index(){
		
			$admin=['name'=>$_SESSION['adminuser'],'id'=>$_SESSION['adminid'],'jid'=>$_SESSION['jid']];
		
			 /* $jiedian = M("rel")
                        ->alias('r')
                       ->field('p.pid')
                        ->join('cm_pro AS p ON r.jid = p.id')
						->where("r.pid={$_SESSION['jid']}")
						->select();
		
          $str=[];
		if(!empty($jiedian)){
			foreach($jiedian as $value){
				if(!in_array($value['pid'],$str)){
					$str[]=$value['pid'];
				}
				
			}
		}*/
		$str=['12','15','20','45','50','33','43','1','28'];
		$this->assign('admin',$admin);
		$this->assign('str',$str);
		$this->display();
    }
	public function main(){
		
		$admin=['name'=>$_SESSION['adminuser'],'id'=>$_SESSION['adminid'],'jid'=>$_SESSION['jid']];
		$user=M('user')->count();
		$goods=M('goods')->count();
		$yuyue=M('yuyue')->count();
		$this->assign('admin',$admin);
		$this->assign('user',$user);
		$this->assign('goods',$goods);
		$this->assign('yuyue',$yuyue);
		$this->display();
    }
	
}