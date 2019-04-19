<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class CommentController extends AllowController
{
    //加载后台模板
     function alist()
	{   
		$User = M('mv_comment'); // 实例化User对象
		$where='IS_DEL=0';
		
		$date=$_GET['date'];
		$date1=$_GET['date1'];
		if(!empty($date)){
			$d=date('Y-m-d',strtotime($date));
			
			$where.=" and GMT_CREATE >'$d'";
		}
		if(!empty($date1)){
			$ds=date('Y-m-d',strtotime($date1));
			
			$where.=" and GMT_CREATE < '$ds' ";
		}
		$p=$_GET['p'];
		if(empty($p)){
			$p=0;
		}
		
	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
		if(!empty($list)){
			foreach($list as $key=>$val){
				if($val['CM_TYPE']==4){
					$video=M('mv_cartoon')->find($val['CM_TYPE_ID']);
					$list[$key]['CONT_TITLE']=$video['CT_NAME'];
					$list[$key]['CM_TYPE']='漫画';
				}else{
					$video=M('mv_movies')->find($val['CM_TYPE_ID']);
					$list[$key]['CONT_TITLE']=$video['MV_NAME'];
					$list[$key]['CM_TYPE']='视频';
				}
				
			}
		}				
	//echo $User->getlastsql();
	
		$this->assign('ad',$list);// 赋值数据集
		$count=  $User->where($where)->count();
		$Page= new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		$this->assign('getall',$_GET);
		$this->assign('page',$show);// 赋值分页输出
		 $this->assign('pg',$p);
	
		$this->display(); // 输出模板
	}
	//删除
	 function del(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			//删除商品图
			M('mv_comment')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','评论删除');
			 $da['msg']="删除成功";
			  $da['href']=U("comment/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("comment/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	
	
	
}
