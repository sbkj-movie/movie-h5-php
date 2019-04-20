<?php
namespace Rzsys\Controller;
use Think\CommController\Controller;

class CartoonController extends AllowController
{
    //加载后台模板
     function alist()
	{   
		$User = M('mv_cartoon'); // 实例化User对象
		$where='IS_DEL=0';
		$name=$_GET['name'];
		$date=$_GET['date'];
		$date1=$_GET['date1'];
		if(!empty($name)){
			$where.=" and CT_NAME like '%{$name}%'";
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
		
		//种类
		$class=M('mv_class')->where('IS_DEL=0 and CL_TYPE=2')->select();
		$newclass=[];
		if(!empty($class)){
			foreach($class as $key=>$val){
				$newclass[$val['ID']]=$val['CL_NAME'];
			}
		}
		$this->assign('class',$newclass);

	
		$list = $User->where($where)->order('ID desc')->page("$p,25")->select();
		if(!empty($list)){
			foreach($list as $key=>$val){
				$pic=json_decode($val['CT_PHOTO_URL']);
				$list[$key]['CT_PHOTO_URL']=$pic['0'];
			}
		}
	//echo $User->getlastsql();
		
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
		$this->assign('getall',$_GET);
		$this->display(); // 输出模板
	}
	//管理员添加
	 function add()
	{   
		//获取请求参数
		$id =$_GET['id'];
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mv_cartoon')->find($id);
			$data['picall']=json_decode($data['CT_PHOTO_URL']);
		}
		//分类
		$data['class']=M('mv_class')->where('IS_DEL=0 and CL_TYPE=2')->select();
		$data['pg']=$_GET['pg'];
		
      $this->assign('data',$data);
		$this->display(); // 输出模板
    }
	function guige(){
		$guige=M('class')->where('pid=0')->select();
		if(!empty($guige)){
			foreach($guige as $key=>$val){
				$pid=$val['id'];
				$guige[$key]['son']=M('class')->where("pid=$pid")->select();
			}
		}
		return $guige;
	}
	//管理员提交
	 function doadd(){
		
		//获取请求参数
		$id=$_POST['id'];
		$pg=$_POST['pg'];
		$data['CT_NAME']=$_POST['name'];
		$data['CT_AUTHOR']=$_POST['author'];
		$data['CT_PHYLETIC']=$_POST['classid'];
		$data['CT_COUNT']=$_POST['zhang'];
		$data['CT_TYPE']=$_POST['type'];
		$data['CT_CATEGORY']=$_POST['lei'];
		$data['CT_PHOTO_URL']=json_encode($_POST['picall']);
		$data['CT_CONTENT']=$_POST['desc'];
	
		//var_dump($data);
		if(isset($id)&&$id!=0){
			$data['GMT_MODIFED']=date('Y-m-d H:i:s',time());
			M('mv_cartoon')->where("ID=$id")->save($data);
			$this->addlog('2','漫画修改');
			$da['msg']="修改成功";
			$da['href']=U("cartoon/alist")."?p=$pg";
		}else{
				
				$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
				M('mv_cartoon')->add($data);
				$this->addlog('1','漫画添加');
				$da['msg']="添加成功";
				$da['href']=U("cartoon/alist")."?p=$pg";
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
			M('mv_cartoon')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','漫画删除');
			 $da['msg']="删除成功";
			  $da['href']=U("cartoon/alist")."?p=$pg";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("cartoon/alist")."?p=$pg";
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
			M('mv_cartoon')->where("ID=$id")->save(['CT_STATUS'=>$state]);
			if($state==1){
				$this->addlog('4','漫画下架');
			}else{
				if($state==0){
					$this->addlog('4','漫画上架');
				}
			}
			
			   $da['msg']="成功";
			  $da['href']=U("cartoon/alist")."?p=$pg";
		}else{
			 $da['msg']="失败";
			$da['href']=U("cartoon/alist")."?p=$pg";
		}
		echo json_encode($da);
	}
	//漫画章节
	function zhangjie(){
		$User = M('mv_cartoon_detail'); // 实例化User对象
		$where='IS_DEL=0';
		$type=$_GET['type'];
		if(!empty($type)){
			$where.=' and CT_ID='.$type;
		}
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
		$manhua=M('mv_cartoon')->where('IS_DEL=0')->select();
		$this->assign('manhua',$manhua);
		//漫画
		$mh = array();
		if(!empty($manhua)){
			foreach($manhua as $val){
				$mh[$val['ID']]=$val['CT_NAME'];
			}
		}
		$this->assign('mh',$mh);
		 $free=['2'=>'会员','1'=>'免费'];
		$this->assign('free',$free);
		$this->display(); // 输出模板
	}
	//漫画章节添加
	function zjadd(){
		//获取请求参数
		$id =$_GET['id'];
		
		$data=array();
		if(isset($id)&&$id!=0){
			$data=M('mv_cartoon_detail')->find($id);
			$data['picall']=json_decode($data['CTD_PHOTO_LIST']);
		}
		$data['manhua']=M('mv_cartoon')->where('IS_DEL=0')->select();
		$data['pg']=$_GET['pg'];
		$type=$_GET['type'];
		if(!empty($type)){
			$data['CT_ID']=$type;
		}
      $this->assign('data',$data);
		$this->display(); // 输出模板
	}

    function zjdoadd()
    {

        //获取请求参数
        $id = $_POST['id'];
        $pg = $_POST['pg'];
        $data['CT_ID'] = $_POST['classid'];
        $data['CTD_TITLE'] = $_POST['name'];

        $data['CTD_TYPE'] = $_POST['type'];
        $data['CTD_PHOTO_URL'] = $_POST['pic'];
        //$data['CTD_PHOTO_LIST']=$_POST['content'];
//		$data['CTD_PHOTO_LIST']=json_encode($_POST['picall']);
        //var_dump($data);

        // 拼装图片地址
        $hostName = $_POST['hostName'];
        $folderName = $_POST['folderName'];
        $ctdNum = $_POST['ctdNum'];
        $startPage = $_POST['startPage'];
        $endPage = $_POST['endPage'];
        $fileSuffix = $_POST['fileSuffix'];

        $picArr = array();
        for ($i = $startPage; $i <= $endPage; $i++) {
            $url = $hostName . "/" . $folderName . "/" . $ctdNum . "-" . $i . "." . $fileSuffix;
            array_push($picArr, $url);
        }
        $data['CTD_PHOTO_LIST']=json_encode($picArr);

        if (isset($id) && $id != 0) {
            $data['GMT_MODIFED'] = date('Y-m-d H:i:s', time());
            M('mv_cartoon_detail')->where("ID=$id")->save($data);
            $da['msg'] = "修改成功";
            $this->addlog('2', '漫画章节修改');
            $da['href'] = U("cartoon/zhangjie") . "?p=$pg&type=$data[CT_ID]";
        } else {

            $data['GMT_CREATE'] = date('Y-m-d H:i:s', time());
            M('mv_cartoon_detail')->add($data);
            $this->addlog('1', '漫画章节添加');
            $da['msg'] = "添加成功";
            $da['href'] = U("cartoon/zhangjie") . "?p=$pg&type=$data[CT_ID]";
        }

        echo json_encode($da);

    }
	function zjdel(){
		//获取请求参数
		$id =$_GET['id'];
		$pg=$_GET['pg'];
		$type=$_GET['type'];
		if(!empty($id)){
			//删除商品图
			M('mv_cartoon_detail')->where("ID=$id")->save(['IS_DEL'=>1]);
			$this->addlog('3','漫画章节删除');
			 $da['msg']="删除成功";
			  $da['href']=U("cartoon/zhangjie")."?p=$pg&type=$data[CT_ID]";
		}else{
			 $da['msg']="删除失败";
			$da['href']=U("cartoon/zhangjie")."?p=$pg&type=$data[CT_ID]";
		}
		echo json_encode($da);
	}
}
