<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class VideoController extends AllowController
{
    //加载后台模板
     function alist()
	{   
		$User = M('mv_movies'); // 实例化User对象
		$where='IS_DEL=0';
		$name=$_GET['name'];
		$date=$_GET['date'];
		$date1=$_GET['date1'];
		if(!empty($name)){
			$where.=" and MV_NAME like '%{$name}%'";
		}
		if(!empty($date)){
			$d=date('Y-m-d',strtotime($date));
			
			$where.=" and GMT_CREATE > '$d' ";
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
	        foreach ($list as $k =>&$v){
	            if(strstr($v['MV_PHYLETIC'],',') !=false){
                    $class_id = explode(',',$v['MV_PHYLETIC']);
                    $wn['ID']=array('in',$class_id);
                    $types =M('mv_class')->where($wn)->getField('CL_NAME',true);
                    $v['MV_PHYLETIC']=implode(',',$types);
                }else{
                    $v['MV_PHYLETIC'] =M('mv_class')->where("ID=".$v['MV_PHYLETIC'])->getField('CL_NAME');
                }
            }

		$this->assign('ad',$list);// 赋值数据集
	
		$count      = $User->where($where)->count();// 查询满足要求的总记录数
		$Page       = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数
		$show       = $Page->show();// 分页显示输出
		
		$this->assign('page',$show);// 赋值分页输出
		$this->assign('pg',$p);
		 
		
		$state=['1'=>'上架','0'=>'已下架'];
		$this->assign('state',$state);
		 $free=['2'=>'会员','1'=>'免费'];
		$this->assign('free',$free);
		
		//种类
		$class=M('mv_class')->where('IS_DEL=0 and CL_TYPE=1')->select();
		$newclass=[];
		if(!empty($class)){
			foreach($class as $key=>$val){
				$newclass[$val['ID']]=$val['CL_NAME'];
			}
		}
	//	dump($newclass);die;
		$this->assign('getall',$_GET);
		$this->assign('class',$newclass);
		
		$this->display(); // 输出模板
	}
	//管理员添加
	 function add()
	{   
		//获取请求参数
		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mv_movies')->find($id);
//			if($data){}
            if(strstr($data['MV_PHYLETIC'],',') !=false){
//                $data['mv_id'] = explode((),',');

                $data['mv_id']=explode(',',$data['MV_PHYLETIC']);
            }
		}
//    dump($data);die;
		//分类
		$data['class']=M('mv_class')->where('IS_DEL=0 and CL_TYPE=1')->select();
		
		$data['pg']=$_GET['pg'];
	
      $this->assign('data',$data);
		$this->display(); // 输出模板
    }
	
	//管理员提交
	 function doadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['MV_NAME']=$_POST['name'];
		$data['MV_TIME']=$_POST['mvtime'];
		$data['MV_HURL']=$_POST['d_href'];
		$data['MV_WURL']=$_POST['w_href'];
		$data['MV_SHURL']=$_POST['sd_href'];
		$data['MV_WHURL']=$_POST['sw_href'];
		$data['MV_PHOTO_URL']=$_POST['pic'];
		$data['MV_PHYLETIC']=$_POST['classid'];
        $data['MV_PHYLETIC'] = implode(",", $_POST['classid']);
		$data['MV_CATEGORY']=$_POST['lei'];
		$data['MV_PLAY_COUNT']=$_POST['click'];
		$data['MV_TYPE']=$_POST['type'];
		$data['MV_CONTENT']=$_POST['desc'];

		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mv_movies')->where("ID=$id")->save($data);
			$this->addlog('2','视频修改');
			$da['msg']="修改成功";
			$da['href']=U("video/alist")."?p=$pg";
		}else{
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mv_movies')->add($data);
				$this->addlog('1','视频添加');
				$da['msg']="添加成功";
				$da['href']=U("video/alist")."?p=$pg";
		}
		
		echo json_encode($da);
		 
	}
	//删除
	 function del(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		if(!empty($id)){
			//删除商品图
			M('mv_movies')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','视频删除');
			 $da['msg']="删除成功";
			  $da['href']=U("video/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("video/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	
	//停用
	 function tuijian(){
		//获取请求参数
		$id = $_GET['id'];
		$state = $_GET['state'];
		$pg=$_GET['pg'];
		if($state==1){
			$state=0;
		}else{
			if($state==0){
				$state=1;
			}
		}
		if(!empty($id)){
			M('mv_movies')->where("ID=$id")->save(['MV_STATUS'=>$state]);
			
			if($state==1){
				$this->addlog('4','视频下架');
			}else{
				if($state==0){
					$this->addlog('4','视频上架');
				}
			}
			   $da['msg']="成功";
			  $da['href']=U("video/alist")."?p=$pg";
		}else{
			 $da['msg']="失败";
			$da['href']=U("video/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	
}
