<?php
namespace Home\Controller;
use Think\Controller;
class AppController extends Controller
{
	
	public function _initialize(){
		header("Access-Control-Allow-Origin: *"); 
		header("Access-Control-Allow-Methods: GET, POST"); 
		header("Access-Control-Allow-Headers: Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With");
	}
	//引导文字
	public function yindao(){
		$type=$_GET['type'];
		$ad=M('mv_setall')->field('CONTENT')->where("TYPE='{$type}'")->find();
		$ad['CONTENT']=preg_replace('/(<img.+?src=")(.*?)/','$1http://'.$_SERVER['SERVER_NAME'].'$2', $ad['CONTENT']);
		
		$da['ad']=$ad;
		
		if($type=='about'){
			$zhang=M('mage_custom_service')->field('ID,CS_TYPE,CS_NUMBER')->where("IS_DEL=0")->select();
			if(empty($zhang)){
				$zhang=0;
			}
			$da['kefu']=$zhang;
		}
		
		$this->returnjson('0','成功！',$da);
	}
	
	//注册
	public function register(){
		$name=$_GET['name'];
		$password=$_GET['password'];
		$pid=$_GET['pid']?$_GET['pid']:0;
		if(empty($name)||empty($password)){
			$this->returnjson('1','用户名和密码不能为空！','');
		}
		$user=M('mv_user')->where("USERNAME='{$name}'")->find();
		if(!empty($user)){
			$this->returnjson('1','该用户名已被占用！','');
		}
		$password=md5($password);
		$data['USERNAME']=$name;
		$data['LOGIN_PSWD']=$password;
		$data['USERIMG']='/Public/admin/images/head/'.rand(1,12).'.png';
		$data['GMT_CREATE']=date('Y-m-d H:i:s',time());
		if($pid!=0){
			$puser=M('mv_user')->find($pid);
			$data['PARENT_CODE']=$puser['EXTENSION_CODE'];
		}
		$ad=M('mv_user')->add($data);
		$id= M('mv_user')->getLastInsID();
		$up['TOKEN']=$this->getToken($id);
		$str='';
		for($i=0;$i<6;$i++){
			$str.=chr(rand(65,90));
		}
		$up['EXTENSION_CODE']='ID'.$id.$str;
		M('mv_user')->where("ID=$id")->save($up);
		$da['uid']=$id;
		$da['token']=md5($id.time());
		$this->returnjson('0','成功！',$da);
	}
	//注册密保问题
	public function requestion(){
		$uid=$_GET['uid'];
		$ques=[];
		$ques[1]['question']=$_GET['question1'];
		$ques[1]['answer']=$_GET['answer1'];
		$ques[2]['question']=$_GET['question2'];
		$ques[2]['answer']=$_GET['answer2'];
		$ques[3]['question']=$_GET['question3'];
		$ques[3]['answer']=$_GET['answer3'];
	
		$data['SECURITY_QUESTION']=json_encode($ques);
		M('mv_user')->where("ID=$uid")->save($data);
		$this->returnjson('0','成功！','');
	}
	//登录
	public function login(){
		$name=$_GET['name'];
		$password=$_GET['password'];
		if(empty($name)||empty($password)){
			$this->returnjson('1','用户名和密码不能为空！','');
		}
		$password=md5($password);
		
		$ad=M('mv_user')->field('ID,USERNAME,USERIMG,TOKEN,TELNO,NO_PRICE,IS_PRICE,SECURITY_QUESTION')->where("USERNAME='{$name}' and LOGIN_PSWD='{$password}' and IS_DEL=0")->find();
		
		if(empty($ad)){
			$this->returnjson('1','用户名密码错误！','');
		}else{
			$taocan=M('mv_shop_history')->field('SH_END')->where("SH_USERID=$ad[ID] and IS_PAY=1")->order('ID desc')->find();
			if(empty($taocan)){
				$ad['taocan']=0;
			}else{
				$ad['taocan']=$taocan['SH_END'];
			}
			if(empty($ad['SECURITY_QUESTION'])){
				$ad['question']=0;
			}else{
				$ad['question']=1;
			}
			//修改token
			$up['TOKEN']=$this->getToken($ad['ID']);
			M('mv_user')->where("ID=$ad[ID]")->save($up);
			$ad['TOKEN']=$up['TOKEN'];
			$this->returnjson('0','成功！',$ad);
		}
	}
	//忘记密码,用户名
	public function checkuser(){
		$name=$_GET['name'];
		$user=M('mv_user')->field('ID')->where("USERNAME='{$name}' and IS_DEL=0")->find();
		if(empty($user)){
			$this->returnjson('1','用户不存在！','');
		}
		$this->returnjson('0','成功！',$user);
		
	}
	//忘记密码,密保问题
	public function pwdwenti(){
		$uid=$_GET['uid'];
		$user=M('mv_user')->field('SECURITY_QUESTION')->find($uid);
		$question=json_decode($user['SECURITY_QUESTION']);
		$que=[];
		foreach($question as $val){
			$que[]=$val->question;
		}
		$this->returnjson('0','成功！',$que);
		
	}
	//忘记密码,密保答案
	public function pwdanswer(){
		$uid=$_GET['uid'];
		$an[1]=$_GET['answer1'];
		$an[2]=$_GET['answer2'];
		$an[3]=$_GET['answer3'];
		$user=M('mv_user')->field('SECURITY_QUESTION')->find($uid);
		$question=json_decode($user['SECURITY_QUESTION']);
		$que=[];
		$i=1;
		$anw=[];
		foreach($question as $val){
			$anw[$i]=$val->answer;
			$i++;
		}
		for($j=1;$j<4;$j++){
			if(trim($anw[$j])!=trim($an[$j])){
					$this->returnjson('1','第'.$j.'个答案错误！','');
			}
		}
		
		$this->returnjson('0','成功！','');
		
	}
	//忘记密码，修改密码
	public function newpwd(){
		$uid=$_GET['uid'];
		$pwd=$_GET['pwd'];
		$data['LOGIN_PSWD']=md5($pwd);
		$user=M('mv_user')->where("ID={$uid}")->save($data);
		if($user){
			$this->returnjson('0','修改成功！','');
		}else{
			$this->returnjson('0','修改失败！','');
		}
		
	}
	//修改登录密码
	public function editmima(){
		$uid=$_GET['uid'];
		$type=$_GET['type']?$_GET['type']:1;
		$an[1]=$_GET['answer1'];
		$an[2]=$_GET['answer2'];
		$an[3]=$_GET['answer3'];
		$user=M('mv_user')->field('SECURITY_QUESTION')->find($uid);
		$question=json_decode($user['SECURITY_QUESTION']);
		$que=[];
		$i=1;
		$anw=[];
		foreach($question as $val){
			$anw[$i]=$val->answer;
			$i++;
		}

        if(trim($anw[1])!=trim($an[1])){
            $this->returnjson('1','答案错误！','');
        }
//		for($j=1;$j<4;$j++){
//			if(trim($anw[$j])!=trim($an[$j])){
//					$this->returnjson('1','第'.$j.'个答案错误！','');
//			}
//		}
		$pwd=$_GET['pwd'];
		if($type==1){
			$data['LOGIN_PSWD']=md5($pwd);
		}else{
			$data['PAY_PSWD']=md5($pwd);
		}
		
		$user=M('mv_user')->where("ID={$uid}")->save($data);
		
			$this->returnjson('0','修改成功！','');
		
	}
	//首页banner
	public function banner(){
		$postion=$_GET['postion']?$_GET['postion']:1;
		$ad=M('mv_banner')->field('ID,BN_NAME,BN_URL,BN_TYPE,BN_TYPE_ID')->where("IS_DEL=0 and BN_POSTION=$postion")->select();
		if(!empty($ad)){
			foreach($ad as $key=>$value){
				$ad[$key]['BN_URL']=$this->signurl($value['BN_URL']);
			}
		}
		$this->returnjson('0','成功！',$ad);
	}
	//首页内容
	public function indexcont(){
		$mianfei=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL')->where("MV_TYPE=1  and IS_DEL=0 and MV_STATUS=1 and MV_PHYLETIC like '%3%' ")->order('GMT_MODIFED desc')->limit(2)->select();
		//echo M('mv_movies')->getlastsql();die();
		if(empty($mianfei)){
			$mianfei=0;
		}else{
			foreach($mianfei as $key=>$value){
				$mianfei[$key]['MV_PHOTO_URL']=$this->signurl($value['MV_PHOTO_URL']);
			}	
		}
		$da['mianfei']=$mianfei;
		$new=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL,MV_TIME,MV_TYPE')->where(" IS_DEL=0  and MV_STATUS=1 and MV_PHYLETIC like '%3%'")->order('ID desc')->limit(4)->select();
		if(empty($new)){
			$new=0;
		}else{
			foreach($new as $key=>$value){
				$new[$key]['MV_PHOTO_URL']=$this->signurl($value['MV_PHOTO_URL']);
			}	
		}
		$da['new']=$new;
		$jingxuan=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL,MV_TYPE')->where(" IS_DEL=0  and MV_STATUS=1 and MV_PHYLETIC like '%2%'")->order('MV_PLAY_COUNT desc')->limit(4)->select();
		if(empty($jingxuan)){
			$jingxuan=0;
		}else{
			foreach($jingxuan as $key=>$value){
				$search = array(" ");
				$replace = array("");
				$value['MV_PHOTO_URL']=str_replace($search, $replace,$value['MV_PHOTO_URL']);
				$jingxuan[$key]['MV_PHOTO_URL']=$this->signurl($value['MV_PHOTO_URL']);
			}	
		}
		$da['jingxuan']=$jingxuan;
		$manhua=M('mv_cartoon')->field('ID,CT_NAME,CT_PHOTO_URL')->where(' IS_DEL=0 and CT_STATUS=1')->order('ID desc')->limit(4)->select();
		if(empty($manhua)){
			$manhua=0;
		}else{
			foreach($manhua as $key=>$val){
				$pic=json_decode($val['CT_PHOTO_URL']);
				$manhua[$key]['CT_PHOTO_URL']=$this->signurl($pic['0']);
			}
		}
		$da['manhua']=$manhua;
		$this->returnjson('0','成功！',$da);
	}
	
	//最新影片
	public function hotmovie(){
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$ad=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL,MV_TIME,MV_TYPE')->where(" IS_DEL=0 and MV_PHYLETIC like '%3%' and MV_STATUS=1")->order('ID desc')->limit($pageindex,$pagesize)->select();
		if(empty($ad)){
			$ad=0;
		}else{
			foreach($ad as $key=>$value){
				$ad[$key]['MV_PHOTO_URL']=$this->signurl($value['MV_PHOTO_URL']);
			}	
		}
		$this->returnjson('0','成功！',$ad);
	}
	
	//精选视频
	public function jingmovie(){
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$ad=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL,MV_TYPE')->where(" IS_DEL=0 and MV_PHYLETIC like '%2%' and MV_STATUS=1")->order('ID desc')->limit($pageindex,$pagesize)->select();
		if(empty($ad)){
			$ad=0;
		}else{
			$uid=$_GET['uid'];
			$token=$_GET['token'];
			if(!empty($uid)&&!empty($token)){
				$this->checktoken($token,$uid);
				//是否收藏
				foreach($ad as $key=>$v){
					$shoucang=M('mv_look_history')->where("LH_LOOKID=$v[ID]  and LH_PHYLETIC=1 and TYPE=2 and IS_DEL=0 and LH_USERID=$uid")->find();
					$ad[$key]['MV_PHOTO_URL']=$this->signurl($v['MV_PHOTO_URL']);
					if(!empty($shoucang)){
						$ad[$key]['shoucang']=1;
					}else{
						$ad[$key]['shoucang']=0;
					}
				}
				
			}else{
				foreach($ad as $key=>$v){
						$ad[$key]['shoucang']=0;
						$search = array(" ");
						$replace = array("");
						$v['MV_PHOTO_URL']=str_replace($search, $replace,$v['MV_PHOTO_URL']);
						$ad[$key]['MV_PHOTO_URL']=$this->signurl($v['MV_PHOTO_URL']);
				}
			}
			
			
			
			
		}
		
		$this->returnjson('0','成功！',$ad);
		
	}
	//精选漫画
	public function jingcartoon(){
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$ad=M('mv_cartoon')->field('ID,CT_NAME,CT_PHOTO_URL')->where(' IS_DEL=0 and CT_STATUS=1')->order('ID desc')->limit($pageindex,$pagesize)->select();
		if(empty($ad)){
			$ad=0;
		}else{
			foreach($ad as $key=>$val){
				$pic=json_decode($val['CT_PHOTO_URL']);
				$ad[$key]['CT_PHOTO_URL']=$this->signurl($pic['0']);
			}
		}
		$this->returnjson('0','成功！',$ad);
	}
	//获取ip位置
	public function ipaddress(){
		$ip=$_GET['ip'];
//		$lang = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=$ip");
//		if(!empty($lang)){
//			$lang=json_decode($lang);
//			//var_dump($lang);die();
//			//echo $lang['data']->country_id;die();
//			$da=$lang->data;
//			if($da->country_id!='CN'){
//				$cn=2;
//			}else{
//				$cn=1;
//			}
//		}else{
//			$cn=2;
//		}

        $file = @file_get_contents(dirname(__FILE__)."/china_ip.txt");
        $arr = explode("\n", $file);

        $cn = 2;

        for ($i = 0; $i < count($arr); $i++) {
            if ($this->judge($ip, $arr[$i])) {
                $cn = 1;
                break;
            }
        }

		$this->returnjson('0','成功！',$cn);
	}
	//视频详情
	public function moviedec(){
		$id=$_GET['id'];
		if(empty($id)){
			$this->returnjson('1','视频不存在！','');
		}
		//echo 111;die();
		
		
		//$lang=file_get_contents("https://www.ip.cn/index.php?ip=$ip");
		$cn=$_GET['cn']?$_GET['cn']:2;
		//var_dump($lang);die();
		
		//echo $cn;die();
		$uid=$_GET['uid'];
		$movie=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL,MV_HURL,MV_WURL,MV_SHURL,MV_WHURL,MV_TIME,MV_PLAY_COUNT,MV_TYPE,MV_PHYLETIC,MV_CONTENT,MV_CATEGORY')->find($id);
		$movie['shikan']=0;
		//修改观看次数
		M('mv_movies')->where("ID=$id")->setInc('MV_PLAY_COUNT',1);
		//推荐
		$ad=M('mv_movies')->field('ID,MV_NAME,MV_PHOTO_URL')->where(' IS_DEL=0 and MV_STATUS=1')->order('MV_PLAY_COUNT desc')->limit(4)->select();
		if(empty($ad)){
			$ad=0;
		}else{
			foreach($ad as $key=>$value){
				$search = array(" ");
				$replace = array("");
				$value['MV_PHOTO_URL']=str_replace($search, $replace, $value['MV_PHOTO_URL']);
				$ad[$key]['MV_PHOTO_URL']=$this->signurl($value['MV_PHOTO_URL']);
			}
		}
		//评论总数
		$ping=M('mv_comment')->where("CM_TYPE=1 and CM_TYPE_ID={$id} and IS_DEL=0")->count();
		if(empty($ping)){
			$ping=0;
		}
		if($ping>10000){
			$ping=round($ping/10000, 1);
		}
		
		$movie['MV_PHOTO_URL']=$this->signurl($movie['MV_PHOTO_URL']);
		$data['tuijian']=$ad;
		$data['comment']=$ping;
		$shoucang=0;
		if(!empty($uid)){
			//是否收藏
			$shoucang=M('mv_look_history')->where("LH_LOOKID=$id  and LH_PHYLETIC=1 and TYPE=2 and IS_DEL=0 and LH_USERID=$uid")->find();
			if(empty($shoucang)){
				$shoucang=0;
			}else{
				$shoucang=1;
			}
		}
		$data['shoucang']=$shoucang;
		$classid=explode(',',$movie['MV_PHYLETIC']);
		//echo $classid['0'];die();
		if(!empty($uid)&&$uid!=0){
			
			$token=$_GET['token'];
			if(empty($uid)||empty($token)){
				$this->returnjson('1','用户参数错误！','');
			}
			$this->checktoken($token,$uid);
			
			if($movie['MV_TYPE']==2){
				//是否是vip
				$time=date('Y-m-d H:i:s',time());
				$vip=M('mv_shop_history')->where("SH_USERID=$uid and SH_END>'$time' and IS_PAY=1")->order('id desc')->find();
				//不是vip，本月是否推荐会员
				if(empty($vip)){
//					$user=M('mv_user')->find($uid);
//					$times=date('Y-m-1',time());
//					$lasttime=date('Y-m-1',strtotime('next month'));
//					$son=M('mv_user')->where("PARENT_CODE='$user[EXTENSION_CODE]' and GMT_CREATE>'$times' and GMT_CREATE<'$lasttime'")->select();
//					if(empty($son)){
//						//没有推荐会员，只能看20部
//						//查看今天是否看过这部
//
//						$totime=date('Y-m-d',time());
//
//						$islook=M('mv_look_history')->where("LH_PHYLETIC=1 and LH_LOOKID=$id and LH_USERID=$uid and TYPE=1 and GMT_MODIFED>'$totime' and LH_CLASS in($movie[MV_PHYLETIC])")->find();
//
//						if(empty($islook)){
//							$looknum=M('mv_look_history')->where("LH_TYPE=2 and LH_PHYLETIC=1 and LH_USERID=$uid and TYPE=1 and GMT_MODIFED>'$totime' and LH_CLASS in($movie[MV_PHYLETIC])")->count();
//							//echo $looknum;
//							//echo M('mv_look_history')->getlastsql();die();
//							//分类
//							$class=M('mv_class')->where("ID in ($movie[MV_PHYLETIC])")->sum('CL_NUM');
//							//echo M('mv_class')->getlastsql();die();
//							//echo $looknum;
//							//echo $class;die();
//							if($looknum>=$class){
//								 $movie['shikan']=1;
//								 if($cn==1){
//									unset($movie['MV_HURL']);
//									unset($movie['MV_WURL']);
//									unset($movie['MV_WHURL']);
//									$movie['url']=$movie['MV_SHURL'];
//								 }else{
//									unset($movie['MV_HURL']);
//									unset($movie['MV_WURL']);
//									unset($movie['MV_SHURL']);
//									$movie['url']=$movie['MV_WHURL'];
//								 }
//								 $movie['url']=$this->signurl($movie['url']);
//								 $data['movie']=$movie;
//								if($class==0){
//									$this->returnjson('3','无权观看，请充值或推荐新会员',$data);
//								}else{
//									$this->returnjson('3','您今天已观看'.$class.'部视频，请充值或推荐新会员！',$data);
//								}
//
//							}
//						}
//
//					}

                    $movie['shikan'] = 1;
                    if ($cn == 1) {
                        unset($movie['MV_HURL']);
                        unset($movie['MV_WURL']);
                        unset($movie['MV_WHURL']);
                        $movie['url'] = $movie['MV_SHURL'];
                    } else {
                        unset($movie['MV_HURL']);
                        unset($movie['MV_WURL']);
                        unset($movie['MV_SHURL']);
                        $movie['url'] = $movie['MV_WHURL'];
                    }
                    $movie['url'] = $this->signurl($movie['url']);
                    $data['movie'] = $movie;
                    $this->returnjson('3','无权观看，请充值 ',$data);
				}
			}
			
			if($cn==1){
				 	unset($movie['MV_SHURL']);
					unset($movie['MV_WURL']);
					unset($movie['MV_WHURL']);
					$movie['url']=$movie['MV_HURL'];
			}else{
				 	unset($movie['MV_HURL']);
					unset($movie['MV_WHURL']);
					unset($movie['MV_SHURL']);
					$movie['url']=$movie['MV_WURL'];
			}
			
			$look=M('mv_look_history')->where("LH_PHYLETIC=1 and LH_USERID=$uid and LH_LOOKID=$id and TYPE=1 and IS_DEL=0")->find();
			if(!empty($look)){
				$up['GMT_MODIFED']=date('Y-m-d H:i:s',time());
				M('mv_look_history')->where("ID=$look[ID]")->save($up);
			}else{
			
				//添加观看记录
				$look=[];
				$look['LH_USERID']=$uid;
				$look['LH_TYPE']=$movie['MV_TYPE'];
				$look['LH_PHYLETIC']=1;
				$look['LH_CLASS']=$classid['0'];
				$look['LH_LOOKID']=$id;
				$look['TYPE']=1;
				$look['GMT_CREATE']=date('Y-m-d H:i:s',time());
				$look['GMT_MODIFED']=date('Y-m-d H:i:s',time());
				M('mv_look_history')->add($look);
				//var_dump($look);die();
			}
			
			
		}else{
			 if($movie['MV_TYPE']==2){
				 $movie['shikan']=1;
				 if($cn==1){
				 	unset($movie['MV_HURL']);
					unset($movie['MV_WURL']);
					unset($movie['MV_WHURL']);
					$movie['url']=$movie['MV_SHURL'];
				 }else{
				 	unset($movie['MV_HURL']);
					unset($movie['MV_WURL']);
					unset($movie['MV_SHURL']);
					$movie['url']=$movie['MV_WHURL'];
				 }
			 }else{
			 	if($cn==1){
				 	unset($movie['MV_SHURL']);
					unset($movie['MV_WURL']);
					unset($movie['MV_WHURL']);
					$movie['url']=$movie['MV_HURL'];
				}else{
						unset($movie['MV_HURL']);
						unset($movie['MV_WHURL']);
						unset($movie['MV_SHURL']);
						$movie['url']=$movie['MV_WURL'];
				}
			 }
		}
		$movie['url']=$this->signurl($movie['url']);
		$data['movie']=$movie;
		
		$this->returnjson('0','成功！',$data);
	}
	function signurl($yurl){
	    // m8u3的文件不加密
        // $suffix = ".m3u8";
        // if (substr_compare($yurl, $suffix, -strlen($suffix)) === 0) {
        //     return $yurl;
        // }

		$urlw=parse_url($yurl);
		$file=substr($urlw['path'],1,strlen($urlw['path']));
		//echo $file;die();
		$host=$urlw['host'];
		$buket=explode('.',$host);//获取视频或者图片地址的请求头   也就是域名根据.分割成数组
		
		$bucketname=$buket['0'];//取数组的第一个值   二级域名的头
		
		   $ak="";
	
		   $sk="";
	
		   $domain="http://".$host."/";//图片域名或bucket域名
	
		   $expire=time()+10800;
		 
		   //$file="029.mp4";//或者"mulu/1.jpg@!样式名"  或者 mulu/1.jpg”
	
		   $StringToSign="GET\n\n\n".$expire."\n/".$bucketname."/".$file;
	
		   $Sign=base64_encode(hash_hmac("sha1",$StringToSign,$sk,true));
		   $Sign=urlencode($Sign);
		   $file=urlencode($file);
		 
		   $url=$yurl."?OSSAccessKeyId=".$ak."&Expires=".$expire."&Signature=".$Sign;
			$url=str_replace("+","%2b",$url);
			//echo $url;die();
		  return $url;
		
	   
	}
	//评论列表
	public function commentlist(){
		$id=$_GET['id'];
		$type=$_GET['type'];
		if(empty($id)||empty($type)){
			$this->returnjson('1','参数错误！','');
		}
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$ad=M('mv_comment')->field('ID,CM_USER_ID,CM_USERNAME,CM_HEADIMG,CM_STAR,CM_CONTENT,GMT_CREATE')->where("CM_TYPE={$type} and CM_TYPE_ID={$id} and IS_DEL=0")->order('ID desc')->limit($pageindex,$pagesize)->select();
		if(empty($ad)){
			$ad=0;
		}else{
			foreach($ad as $key=>$val){
				$ad[$key]['GMT_CREATE']=date('Y-m-d H:i',strtotime($val['GMT_CREATE']));
				$ad[$key]['CM_HEADIMG']=substr($val['CM_HEADIMG'],1,strlen($val['CM_HEADIMG'])); 
			}
		}
		
		$this->returnjson('0','成功！',$ad);
	}
	//发布评论
	public function addcomment(){
		$id=$_GET['id'];
		$type=$_GET['type'];
		if(empty($id)||empty($type)){
			$this->returnjson('1','参数错误！','');
		}
		//查询用户评论次数
		
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$pnum=M('mv_comment')->where("CM_TYPE=$type and CM_TYPE_ID=$id and CM_USER_ID=$uid")->count();
		if($pnum>=2){
			$this->returnjson('1','最多只能评论两条！','');
		}
		
		
		$xing=$_GET['xing'];
		if(empty($_GET['content'])||empty($xing)){
			$this->returnjson('1','评论内容或星级不能为空！','');
		}
		$user=M('mv_user')->find($uid);
		$add=[];
		$add['CM_TYPE']=$type;
		$add['CM_TYPE_ID']=$id;
		$add['CM_USER_ID']=$uid;
		$add['CM_USERNAME']=$user['USERNAME'];
		$add['CM_HEADIMG']=$user['USERIMG'];
		$add['CM_STAR']=$xing;
		$add['CM_CONTENT']=$_GET['content'];
		$add['GMT_CREATE']=date('Y-m-d H:i:s',time());
		//var_dump($add);die();
		if(M('mv_comment')->add($add)){
			$this->returnjson('0','评论成功！','');
		}else{
			$this->returnjson('1','添加失败！','');
		}
		
	}
	//漫画详情
	public function cartoondec(){
		$id=$_GET['id'];
		if(empty($id)){
			$this->returnjson('1','漫画不存在！','');
		}
		//是否有观看记录
			
		$cartoon=M('mv_cartoon')->field('ID,CT_NAME,CT_PHOTO_URL,CT_CONTENT,CT_AUTHOR,CT_TYPE,CT_CATEGORY,CT_COUNT')->find($id);
		$photo=json_decode($cartoon['CT_PHOTO_URL']);
		if(!empty($photo)){
			foreach($photo as $key=>$v){
				$photo[$key]=$this->signurl($v);
			}
		}
		$cartoon['CT_PHOTO_URL']=$photo;
		//评论总数
		$ping=M('mv_comment')->where("CM_TYPE=2 and CM_TYPE_ID={$id} and IS_DEL=0")->count();
		$data['cartoon']=$cartoon;
		//$data['tuijian']=$ad;
		if(empty($ping)){
			$ping=0;
		}
		$data['comment']=$ping;
		$uid=$_GET['uid'];
		$shoucang=0;
		if(!empty($uid)){
			//是否收藏
			$shoucang=M('mv_look_history')->where("LH_LOOKID=$id  and LH_PHYLETIC=2 and TYPE=2 and IS_DEL=0 and LH_USERID=$uid")->find();
			if(empty($shoucang)){
				$shoucang=0;
			}else{
				$shoucang=1;
			}
		}
		
		$data['shoucang']=$shoucang;
		$this->returnjson('0','成功！',$data);
	}
	//漫画章节
	public function  cardetial(){
		$id=$_GET['id'];
		if(empty($id)){
			$this->returnjson('1','漫画不存在！','');
		}
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		//章节列表
		$zhang=M('mv_cartoon_detail')->field('ID,CT_ID,CTD_TITLE,CTD_PHOTO_URL,CTD_TYPE')->where(" IS_DEL=0 and CT_ID=$id")->order('ID asc')->limit($pageindex,$pagesize)->select();
		
		if(empty($zhang)){
			$zhang=0;
		}else{
			foreach($zhang as $key=>$val){
				$zhang[$key]['CTD_PHOTO_URL']=$this->signurl($val['CTD_PHOTO_URL']);
			}
		}
		
		$this->returnjson('0','成功！',$zhang);
	}
	//漫画章节详情
	public function  zhangdesc(){
		$id=$_GET['id'];
		$cid=$_GET['cid'];
		$next=$_GET['next'];
		$last=$_GET['last'];
		if(empty($cid)){
			$this->returnjson('1','漫画不存在！','');
		}
		if($next==1){
			$where=" IS_DEL=0 and CT_ID=$cid and ID>$id";
			$order="ID asc";
			$detail='没有更多章节了';
		}else if($last==1){
			$where=" IS_DEL=0 and CT_ID=$cid and ID<$id";
			$order="ID desc";
			$detail='当前为第一章';
		}else{
			$where=" IS_DEL=0 and CT_ID=$cid and ID=$id";
			$order="ID asc";
		}
		$uid=$_GET['uid'];
		$zhang=M('mv_cartoon_detail')->field('ID,CT_ID,CTD_TITLE,CTD_TYPE,CTD_PHOTO_LIST')->where($where)->order($order)->find();
		//章节列表
		if(empty($zhang)){
			$this->returnjson('1',$detail,'');
		}
		//$zhang['CTD_PHOTO_URL']=$this->signurl($val['CTD_PHOTO_URL']);
		//$zhang['CTD_PHOTO_LIST']=preg_replace('/(<img.+?src=")(.*?)/','$1http://'.$_SERVER['SERVER_NAME'].'$2', $zhang['CTD_PHOTO_LIST']);
		
		$photo=json_decode($zhang['CTD_PHOTO_LIST']);
		if(!empty($photo)){
			foreach($photo as $key=>$v){
				$photo[$key]=$this->signurl($v);
			}
		}
		$zhang['CTD_PHOTO_LIST']=$photo;
		if(!empty($uid)&&$uid!=0){
			if($zhang['CTD_TYPE']==2){
				$this->checkvip($uid);
			}
			$token=$_GET['token'];
			if(empty($uid)||empty($token)){
				$this->returnjson('1','用户参数错误！','');
			}
			$this->checktoken($token,$uid);
			$look=M('mv_look_history')->where("LH_PHYLETIC=2 and LH_USERID=$uid and LH_LOOKID=$cid and TYPE=1 and IS_DEL=0")->find();
			//echo M('mv_look_history')->getlastsql();die();
			if(!empty($look)){
				$look['GMT_MODIFED']=date('Y-m-d H:i:s',time());
				M('mv_look_history')->where("ID=$look[ID]")->save($look);
			}else{
				//添加观看记录
				$look=[];
				$look['LH_USERID']=$uid;
				$look['LH_TYPE']=$zhang['CTD_TYPE'];
				$look['LH_PHYLETIC']=2;
				$look['LH_CLASS']=2;
				$look['LH_LOOKID']=$cid;
				$look['TYPE']=1;
				$look['GMT_CREATE']=date('Y-m-d H:i:s',time());
				$look['GMT_MODIFED']=date('Y-m-d H:i:s',time());
				M('mv_look_history')->add($look);
			}
			
			
		}else{
			if($zhang['CTD_TYPE']==2){
				$this->returnjson('3','请充值！','');
			}
		}
		
		$this->returnjson('0','成功！',$zhang);
	}
	//个人中心
	public function  userinfo(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		
		//章节列表
		$zhang=M('mv_user')->field('ID,USERNAME,TELNO,USERIMG,NO_PRICE,IS_PRICE')->find($uid);
		if(empty($zhang)){
			$zhang=0;
		}else{
			$zhang['USERIMG']=substr($zhang['USERIMG'],1,strlen($zhang['USERIMG'])); 
			//会员
			$nowtime=date('Y-m-d H:i:s',time());
			$taocan=M('mv_shop_history')->field('SH_END')->where("SH_USERID=$uid and IS_PAY=1 and SH_END>'$nowtime'")->order('ID desc')->find();
			if(empty($taocan)){
				$user=M('mv_user')->find($uid);
				$times=date('Y-m-1',time());
				$lasttime=date('Y-m-1',strtotime('next month'));
				$son=M('mv_user')->where("PARENT_CODE='$user[EXTENSION_CODE]' and GMT_CREATE>'$times' and GMT_CREATE<'$lasttime'")->select();
				//echo M('mv_user')->getlastsql();die();
				if(empty($son)){
					$zhang['taocan']=0;
				}else{
					$zhang['taocan']=$lasttime;
					
				}
				
			}else{
				$zhang['taocan']=$taocan['SH_END'];
			}
		}
		
		$this->returnjson('0','成功！',$zhang);
	}
	//套餐
	public function  taocan(){
	
		//章节列表
		$zhang=M('mage_shop')->field('ID,SP_NAME,SP_PRICE,SP_END_TIME')->where(' IS_DEL=0')->order('ID asc')->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//购买记录
	public function shop_buy(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		//购买记录
		$zhang=M('mv_shop_history')->field('ID,SH_USERID,SH_NAME,SH_PAY,GMT_CREATE')->where("SH_USERID={$uid} and  IS_DEL=0 and IS_PAY=1")->order('ID desc')->select();
		
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//获取银行卡信息
	public function bankfile(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$id=$_GET['id'];
		if(!empty($id)&&$id!=0){
			$where=" and ID=$id";
		}else{
			$where='';
		}
		$bank=M('mv_bank_card')->field('ID,BC_NUMBER,BC_OPEN_BANK')->where("BC_USERID=$uid and IS_DEL=0".$where)->order('ID desc')->find();
		if(empty($bank)){
			$bank=0;
		}else{
			$bank['BC_NUMBER']=substr($bank['BC_NUMBER'],0,4).'******'.substr($bank['BC_NUMBER'],-4);
		}
		$money=M('mv_user')->field('NO_PRICE')->find($uid);
		//可提现金额
		$data['bank']=$bank;
		$data['money']=$money['NO_PRICE'];
		
		$this->returnjson('0','成功！',$data);
	}
	//提现
	public function tixian(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$mima=$_GET['mima'];
		if(empty($mima)){
			$this->returnjson('1','交易密码不能为空！','');
		}
		$mima=md5($mima);
		$user=M('mv_user')->find($uid);
		
		if(empty($user['PAY_PSWD'])){
			$this->returnjson('1','请先设置交易密码！','');
		}
		if($user['PAY_PSWD']!=$mima){
			$this->returnjson('1','交易密码错误！','');
		}
		$add['PF_USER_ID']=$uid;
		$add['PF_PRICE']=$_GET['money'];
		if($user['NO_PRICE']<$_GET['money']){
			$this->returnjson('1','提现金额不能大于账户余额！','');
		}
		$add['PF_BANK_CARD_ID']=$_GET['bid'];
		$add['GMT_CREATE']=date('Y-m-d H:i:s',time());
		if(M('mv_put_forward')->add($add)){
			//修改用户金额
			$money=$user['NO_PRICE']-$add['PF_PRICE'];
			$fmoney=$user['IS_PRICE']+$add['PF_PRICE'];
			M('mv_user')->where("ID=$uid")->save(['NO_PRICE'=>$money,'IS_PRICE'=>$fmoney]);
			$this->returnjson('0','提现申请已提交，等待处理！','');
		}else{
			$this->returnjson('1','提现失败！','');
		}
	}
	//购买记录
	public function tixianlist(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		//购买记录
		$zhang=M('mv_put_forward')->field('ID,PF_PRICE,PF_STATUS,GMT_CREATE')->where("IS_DEL=0 and PF_USER_ID=$uid ")->order('ID desc')->limit($pageindex,$pagesize)->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//绑定银行卡
	public function addbank(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$add['BC_USERID']=$uid;
		$add['BC_NUMBER']=$_GET['number'];
		$add['BC_NAME']=$_GET['name'];
		$add['BC_TELNO']=$_GET['telphone'];
		$add['BC_OPEN_BANK']=$_GET['bankname'];
		$add['BC_OPEN_BANK_ADDRESS']=$_GET['address'];
		$add['GMT_CREATE']=date('Y-m-d H:i:s',time());
		
		if(M('mv_bank_card')->add($add)){
			$this->returnjson('0','绑定成功！','');
		}else{
			$this->returnjson('1','添加失败！','');
		}
	}
	//银行卡管理
	public function banklist(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		
		$zhang=M('mv_bank_card')->field('ID,BC_USERID,BC_NUMBER,BC_OPEN_BANK,BC_NAME')->where(" IS_DEL=0 and BC_USERID=$uid")->order('ID asc')->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//银行卡删除
	public function delbank(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$id=$_GET['id'];
		if(empty($id)){
			$this->returnjson('1','参数错误！','');
		}
		$zhang=M('mv_bank_card')->where("ID=$id and BC_USERID=$uid")->save(['IS_DEL'=>1]);
		if($zhang){
			$this->returnjson('0','删除成功！','');
		}else{
			$this->returnjson('1','删除失败！','');
		}
	}
	//我的下级
	public function xiaji(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$where='';
		
		if(!empty($_GET['keywords'])){
			$where=" and USERNAME like '%{$_GET[keywords]}%'";
		}
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$user=M('mv_user')->find($uid);
		$son=M('mv_user')->field('ID,USERNAME,USERIMG')->where("PARENT_CODE='{$user['EXTENSION_CODE']}'".$where)->limit($pageindex,$pagesize)->select();
		
		if(empty($son)){
			$son=0;
		}
		$this->returnjson('0','成功！',$son);
		
	}
	//推广收益
	public function shouyi(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$zhang=M('mv_user_profit')->field('ID,UP_RUN_USERID,UP_PRICE,GMT_CREATE')->where("IS_DEL=0 and UP_RUN_USERID={$uid}")->order('ID asc')->limit($pageindex,$pagesize)->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//观看记录
	public function lookjilu(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$type=$_GET['type'];
		if(empty($type)){
			$this->returnjson('1','参数错误！','');
		}
		$looktype=$_GET['looktype']?$_GET['looktype']:1;
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		if($type==1){
			$good = M("mv_look_history")
                        ->alias('l')
                       ->field('m.ID,m.MV_NAME as name,m.MV_PHOTO_URL as pic,m.MV_CONTENT as content,l.ID as lookid,l.LH_TYPE as type')
                        ->join('mv_movies AS m ON l.LH_LOOKID = m.ID')
						->where("l.LH_USERID=$uid and l.TYPE=$looktype and l.LH_PHYLETIC=1 and m.IS_DEL=0 and l.IS_DEL=0 and m.MV_STATUS=1")
						->limit($pageindex,$pagesize)
						->order('l.GMT_CREATE desc')
						->select();
			if(!empty($good)){
				foreach($good as $key=>$val){
					$good[$key]['pic']=$this->signurl($val['pic']);
				}
			}
				
		}else{
			$good = M("mv_look_history")
                        ->alias('l')
                       ->field('m.ID,m.CT_NAME as name,m.CT_PHOTO_URL as pic,m.CT_CONTENT as content,l.ID as lookid,l.LH_TYPE as type')
                        ->join('mv_cartoon AS m ON l.LH_LOOKID = m.ID')
						->where("l.LH_USERID=$uid and l.TYPE=$looktype and l.LH_PHYLETIC=2 and m.IS_DEL=0 and l.IS_DEL=0 and m.CT_STATUS=1")
						->limit($pageindex,$pagesize)
						->order('l.GMT_CREATE desc')
						->select();
			//echo M("mv_look_history")->getlastsql();
					
			if(!empty($good)){
				foreach($good as $key=>$val){
					$pic=json_decode($val['pic']);
					$good[$key]['pic']=$this->signurl($pic['0']);
		
				}
			}
		}
		
		if(empty($good)){
			$good=0;
		}
		//var_dump($good);die();
		$this->returnjson('0','成功！',$good);
	}
	//观看记录删除
	public function dellook(){
		$uid=$_GET['uid'];
		$token=$_GET['token'];
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$id=$_GET['id'];
		$type=$_GET['type']?$_GET['type']:1;
		if(empty($id)){
			$this->returnjson('1','参数错误！','');
		}
		$zhang=M('mv_look_history')->where("ID=$id and LH_USERID=$uid and TYPE=$type")->save(['IS_DEL'=>1]);
		
		if($zhang){
			$this->returnjson('0','删除成功！','');
		}else{
			$this->returnjson('1','删除失败！','');
		}
	}
	//客服信息
	function kefu(){
		$zhang=M('mage_custom_service')->field('ID,CS_TYPE,CS_NUMBER')->where("IS_DEL=0")->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//收藏
	public function  shoucang(){
		
		$uid=$_GET['uid'];
		$token=$_GET['token'];
			if(empty($uid)||empty($token)){
				$this->returnjson('1','用户参数错误！','');
			}
			
			$this->checktoken($token,$uid);
			$id=$_GET['id'];
			$type=$_GET['type'];
			//是否收藏
			$shoucang=M('mv_look_history')->where("LH_LOOKID=$id  and LH_PHYLETIC=$type and TYPE=2 and IS_DEL=0 and LH_USERID=$uid")->find();
			if(!empty($shoucang)){
				M('mv_look_history')->where("LH_LOOKID={$id} and LH_PHYLETIC={$type} and TYPE=2 and LH_USERID=$uid")->save(['IS_DEL'=>1]);
				$da['shoucang']=0;
				$this->returnjson('0','取消收藏！',$da);
			}
			//添加收藏记录
			$look=[];
			$look['LH_USERID']=$uid;
			if($type==1){
				$zhang=M('mv_movies')->find($id);
				$look['LH_TYPE']=$zhang['MV_TYPE'];
			}else{
				$zhang=M('mv_cartoon')->find($id);
				$look['LH_TYPE']=$zhang['CTD_TYPE'];
			}
			
			$look['LH_PHYLETIC']=$type;
			$look['LH_LOOKID']=$id;
			$look['TYPE']=2;
			$look['GMT_CREATE']=date('Y-m-d H:i:s',time());
			$add=M('mv_look_history')->add($look);
			if($add){
				$da['shoucang']=1;
				$this->returnjson('0','收藏成功！',$da);
			}else{
				$this->returnjson('1','收藏失败！','');
			}
	}
	
	//搜索
	function searchkey(){
		$keywords=$_GET['keywords'];
		if(empty($keywords)&&($keywords!=0)){
			$this->returnjson('1','搜索内容不存在！','');	
		}
			$k='';
			//if(!empty($keywords)){
				$k=" and MV_NAME like '%{$keywords}%'";
			//}
			$movies=M('mv_movies')->field('ID,MV_NAME as name,MV_PHOTO_URL as pic,MV_TYPE as type,MV_CONTENT as detail')->where(' IS_DEL=0  and MV_STATUS=1'.$k)->order('ID desc')->limit(30)->select();
			//echo M('mv_movies')->getlastsql();die();
			if(empty($movies)){
				$movies=0;
			}else{
				foreach($movies as $key=>$val){
					$movies[$key]['pic']=$this->signurl($val['pic']);
				}
			}
	
			$k1='';
			//if(!empty($keywords)){
				$k1=" and CT_NAME like '%{$keywords}%'";
			//}
			$cartoon=M('mv_cartoon')->field('ID,CT_NAME as name,CT_PHOTO_URL,CT_CONTENT as detail')->where(' IS_DEL=0 and CT_STATUS=1'.$k1)->order('ID desc')->limit(30)->select();
			if(empty($cartoon)){
				$cartoon=0;
			}else{
				foreach($cartoon as $key=>$val){
					$pic=json_decode($val['CT_PHOTO_URL']);
					$cartoon[$key]['pic']=$this->signurl($pic['0']);
				}
			}
		$da['movie']=$movies;
		$da['cartoon']=$cartoon;
		$this->returnjson('0','成功！',$da);
		
	}

    /**
     * 支付通道
     */
    function payChannel() {
        $data = M('mage_hupijiao')->field('ID as id, HU_NAME as name, HU_TYPE as type, SORT_NUM as sort')->where("IS_DEL=0")->order('SORT_NUM')->select();
        $this->returnjson('0', '成功！', $data);
    }

    //购买
    function pay() {
        $uid = $_GET['uid'];
        $token = $_GET['token'];
        if (empty($uid) || empty($token)) {
            //$this->returnjson('1','用户参数错误！','');
        }
        $this->checktoken($token, $uid);
        //$WxData = new \Org\Common\XH_Payment_Api();
        $type = $_GET['type'];//1：青苹果支付，2：宝付支付

//		$title='VIP会员购买';
        if (empty($type)) {
            $this->returnjson('1', '支付方式和支付金额不能为空！', '');
        }

        $tid = $_GET['tid'];//套餐id
        $domain = $_GET['domain'];//域名
        if (empty($tid)) {
            $this->returnjson('1', '购买套餐不能为空！', '');
        }

        //套餐
        $movies = M('mage_shop')->field('ID,SP_NAME,SP_PRICE,SP_END_TIME')->where("IS_DEL=0 and ID=$tid")->order('ID desc')->find();
        $money = $movies['SP_PRICE'];

        if ($money < 10) {
            $this->returnjson('1', '最小金额为10元！', '');
        }

        $hupijiao = M('mage_hupijiao')->where("IS_DEL=0 and `MAX_MONEY`>(`NOW_MONEY`+$money) and HU_TYPE=$type")->order('rand()')->find();

        if (empty($hupijiao)) {
            $this->returnjson('1', '暂时不能支付，请联系管理员！', '');
        }

        //是否有效套餐
        $stime = date('Y-m-d H:i:s', time());
        $istaocan = M('mv_shop_history')->where("SH_USERID=$uid and IS_PAY=1 and SH_END>'$stime'")->order('ID desc')->find();
        //添加订单
        $order['SH_USERID'] = $uid;
        $order['SHID'] = $tid;
        $order['HPID'] = $hupijiao['ID'];
        $order['SH_NAME'] = $movies['SP_NAME'];
        $order['SH_PRICE'] = $movies['SP_PRICE'];
        $order['SH_PAY'] = $money;
        if (!empty($istaocan)) {
            $order['SH_END'] = date('Y-m-d H:i:s', (strtotime($istaocan['SH_END']) + $movies['SP_END_TIME'] * 24 * 3600));
        } else {
            $order['SH_END'] = date('Y-m-d H:i:s', (time() + $movies['SP_END_TIME'] * 24 * 3600));
        }

        $order['GMT_CREATE'] = date('Y-m-d H:i:s', time());

        $ad = M('mv_shop_history')->add($order);
        $oid = M('mv_shop_history')->getLastInsID();

        if (!empty($oid)) {
            $up['SH_ORDER'] = $uid . '-' . $oid . '-' . time();
            M('mv_shop_history')->where("ID=$oid")->save($up);


            $trade_order_id = $up['SH_ORDER'];//商户网站内部ID，此处time()是演示数据
            $appid = $hupijiao['HU_APPID'];//测试账户，
            $appsecret = $hupijiao['HU_APP_SECRET'];//测试账户，
//            $my_plugin_id = 'my-plugins-id';
            if ($_GET['f'] && $_GET['f'] == 1) {
                $return_url = 'http://' . $_SERVER['SERVER_NAME'] . '/users/appPaySuccess.html';
//				$callback_url=$domain.'/users/appPayerr.html';
            } else {
                $return_url = 'http://' . $_SERVER['SERVER_NAME'] . '/users/webPaySuccess.html';
//				$callback_url=$domain.'/users/webPayerr.html';
            }

            if ($type == 1 || $type == 3) {

                $payMethod = 2;
                $paytype = '21';

                if ($type == 3) {
                    $payMethod = 1;
                    $paytype = 11;
                }

                $data = [
                    "orderAmount" => $money, //金额
                    "orderId" => $trade_order_id,//订单号
                    "merchant" => $appid, //商户号
                    "payMethod" => $payMethod,//支付方式可选值，1：微信支付，2：支付宝支付
                    "payType" => $paytype,//payType 11微信扫码支付，21支付宝扫码支付，22支付宝PC扫码，23支付宝手机wap
                    "signType" => "MD5",
                    "version" => "1.0",
                    "outcome" => "no",
                ];
                $key = $appsecret; //key
                ksort($data);//函数对关联数组按照键名进行升序排序。
                $postString = http_build_query($data);//返回一个 URL 编码后的字符串。

                $signMyself = strtoupper(md5($postString . $key));
                $data["sign"] = $signMyself;
                $data['productName'] = '会员充值';
                $data['isLoop'] = 'yes';
                $data['productDesc'] = '订单' . $trade_order_id;
                $data['createTime'] = time();
                $data['returnUrl'] = $return_url;
                $data['notifyUrl'] = 'https://' . $_SERVER['SERVER_NAME'] . '/index.php/app/notify.html';
                $postString = http_build_query($data);
                $url = "http://api.hypay.xyz/index.php/Api/Index/createOrder?" . $postString;
                $pay_url = $url;
                $this->returnjson('4', '成功！', $pay_url);
            } elseif ($type == 2) {

                $user = M('mv_user')->where("ID='{$uid}'")->find();

                $param_arr = array(
                    'outTradeNo' => $trade_order_id,  // 每个outTradeNo只能用一次，否则会因为订单重复而失败
                    'orderAmountRmb' => $money,
                    'merchantName' => $appid,
                    'vipName' => $user['USERNAME'],
                    'subject' => 'VIP会员购买',
                    'body' => 'VIP会员购买',
                    'notifyUrl' => 'https://' . $_SERVER['SERVER_NAME'] . '/index.php/app/receive_message.html',//具体的回调地址
                    'signType' => 'RSA'

                );
                //var_dump($param_arr);die();
                ksort($param_arr);
                $str_to_sign = $this->make_json_join_str($param_arr);

                $str_signed = $this->sign($str_to_sign, $appsecret);
                $param_arr['sign'] = $str_signed;
                $pre_pay_url = "https://api.qapple.io/v2/api/merchant/merchantcenter/pay/prePay";
                $response_str = $this->request_post($pre_pay_url, $param_arr);
                $response = json_decode($response_str, true);
                if ($response['code'] != 200) {
                    //echo 'request failed, server returns: '.$response_str;die();
                    $this->returnjson('1', '失败！', 'request failed, server returns: ' . $response_str);
                } else {
                    $request_data = $response['data'];
                    $request_signed = $request_data['sign'];
                    unset($request_data['sign']);
                    ksort($request_data);
                    $request_unsigned = $this->make_json_join_str($request_data);

                    $check_sign = $this->do_check($request_unsigned, $request_signed, $hupijiao['HU_APP_GONG']);  // 这里使用response的数据
                    if ($check_sign) {
                        $pay_url = $request_data['returnUrl'];
                        $this->returnjson('4', '成功！', $pay_url);
                    } else {
                        $this->returnjson('1', '验签失败！');
                    }
                }
            } else {
                $this->returnjson('1', '订单错误，请重新提交！', '');
            }

        } else {
            $this->returnjson('1', '订单错误，请重新提交！', '');
        }

    }

	//青苹果支付通知
	function receive_message() {
        //获取request body里面的内容
		$body = file_get_contents('php://input');
		$post = json_decode($body, true);
	
		$data = $post;
		unset($data['sign']);
	
		ksort($data);
		$request_unsigned = $this->make_json_join_str($data);
			$trade_order_id =$data['outTradeNo'];
				$array=explode('-',$data['outTradeNo']);
				$uid=$array[0];
				$oid=$array[1];
				$order=M('mv_shop_history')->find($oid);
				$hupijiao=M('mage_hupijiao')->find($order['HPID']);
				$key=$hupijiao['HU_APP_GONG'];
		   $check_sign = $this->do_check($request_unsigned, $post['sign'],$key);  // 这里使用response的数据
		//这里返回1是正确的
		if($check_sign ==1 ){
			//返回给青苹果的数据，根据每个框架不同返回就可以
				if($data['status']=='SUCCESS'){
					$up['IS_PAY']=1;
					M('mv_shop_history')->where("ID=$oid")->save($up);
					//修改最高限额
					$xiane['NOW_MONEY']=$hupijiao['NOW_MONEY']+$order['SH_PAY'];
					
					M('mage_hupijiao')->where("ID=$order[HPID]")->save($xiane);
				}
			
				
				
		}

	}
	//支付通知
	function notify(){
			$json = file_get_contents('php://input');
			//$key =  ' 1 ';
			$arr = json_decode($json,true);
			$paramsJson = $arr['paramsJson'];
			
			//商户订单ID
				$data=$paramsJson['data'];
				//$myfile = fopen(dirname(__FILE__)."/notify4.text", "w") or die("Unable to open file!");
				//fwrite($myfile, $data);
				//fclose($myfile);
				
				$trade_order_id =$data['orderId'];
				$array=explode('-',$data['orderId']);
				$uid=$array[0];
				$oid=$array[1];
				$order=M('mv_shop_history')->find($oid);
				$hupijiao=M('mage_hupijiao')->find($order['HPID']);
				$key=$hupijiao['HU_APP_SECRET'];
			$jsonBase64 = base64_encode(json_encode($arr['paramsJson']));
			$jsonBase64Md5 = md5($jsonBase64);
			$sign = strtoupper(md5($key.$jsonBase64Md5));
			if($sign != $arr['sign']){
				echo 'error';
				$myfile = fopen(dirname(__FILE__)."/notify0.text", "w") or die("Unable to open file!");
	       		fwrite($myfile, $json);
		 		fclose($myfile);
				exit();
			}

			if($paramsJson['code'] != '000000'){
				echo 'error';
				$myfile = fopen(dirname(__FILE__)."/notify1.text", "w") or die("Unable to open file!");
	       		fwrite($myfile, $json);
		 		fclose($myfile);
				exit();
			} 
			
			//$myfile = fopen(dirname(__FILE__)."/notify2.text", "w") or die("Unable to open file!");
			//fwrite($myfile, $_SERVER['REMOTE_ADDR']);
			//fclose($myfile);
			if($_SERVER['REMOTE_ADDR'] == "122.14.227.221"||$_SERVER['REMOTE_ADDR'] == "122.14.195.188"){
				
				$up['IS_PAY']=1;
				M('mv_shop_history')->where("ID=$oid")->save($up);
				//修改最高限额
				$xiane['NOW_MONEY']=$hupijiao['NOW_MONEY']+$order['SH_PAY'];
				
				M('mage_hupijiao')->where("ID=$order[HPID]")->save($xiane);
				//$myfile = fopen(dirname(__FILE__)."/notify3.text", "w") or die("Unable to open file!");
				//fwrite($myfile, $trade_order_id);
				//fclose($myfile);
			}
			
	}
	 public function upload(){
		$ajax=$_POST['ajax'];
		$ajax=json_decode($ajax);
		
		$uid=$ajax->uid;
		$token=$ajax->token;
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
        $path = "Public/upload/".date("Y-m-d")."/";
		if(!is_dir($path)){
			mkdir($path,0777);
		}
		$base64_img = trim($ajax->pic);
		if(preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_img, $result)){
		  $type = $result[2];
		  if(in_array($type,array('pjpeg','jpeg','jpg','gif','bmp','png'))){
			$imgname  = date("Y_m_d_H_i_s")."_".rand(10000,99999).".".$type;
			$new_file=$path.$imgname;
			
			if(file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_img)))){
			 
			  	$up['USERIMG']='/'.$new_file;
				M('mv_user')->where("ID=$uid")->save($up);
				$this->returnjson('0','上传成功！','');
			}else{
				  $this->returnjson('1','图片上传失败！','');
		 
			}
		  }else{
			//文件类型错误
		   $this->returnjson('1','文件类型错误！','');
		  }
		 
		}else{
		  //文件错误
		 $this->returnjson('1','文件错误！','');
		}
			

		
    }
	public function appupload(){
		$uid=$_POST['uid'];
		$token=$_POST['token'];
		//$this->returnjson('1','图片格式错误！',$_POST);
		if(empty($uid)||empty($token)){
			$this->returnjson('1','用户参数错误！','');
		}
		$this->checktoken($token,$uid);
		$path = "Public/upload/".date("Y-m-d")."/";
		if(!is_dir($path)){
			mkdir($path,0777);
		}
		$extArr = array("jpg", "png", "gif" , "jpeg");
		//echo json_encode($_FILES);die();
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
			$name = $_FILES['pic']['name'];
			$size = $_FILES['pic']['size'];
			
			if(empty($name)){
				$this->returnjson('1','请选择要上传的图片！','');
			}
			$extend = pathinfo($name);
			$ext = strtolower($extend["extension"]);
			
			if(!in_array($ext,$extArr)){
				$this->returnjson('1','图片格式错误！','');
			}
			
			$image_name = date("Y_m_d_H_i_s")."_".rand(10000,99999).".".$ext;
			$tmp = $_FILES['pic']['tmp_name'];
			$pathn=ltrim($path,'.');
			$fileurl = $pathn.$image_name;
			
			if(@move_uploaded_file($tmp, $path.$image_name)){
				$up['USERIMG']='/'.$path.$image_name;
				M('mv_user')->where("ID=$uid")->save($up);
				$this->returnjson('0','上传成功！','');
			}else{
				$this->returnjson('1','上传出错！','');
			}
			
		}
		$this->returnjson('1','上传出错！','');
		
    }
	//域名列表
	function  yuminglist(){
		$zhang=M('mage_look_url')->field('ID,LU_NAME,LU_REALM,LU_OTHER')->where("IS_DEL=0")->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//版本
	function  banben(){
		$type=$_GET['type']?$_GET['type']:1;
		$zhang=M('mage_app')->field('*')->where("IS_DEL=0 and APP_TYPE=$type")->order('ID DESC')->find();
		//echo M('mage_app')->getlastsql();die();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//判断token
	function  checktoken($token,$uid){
		$ad=M('mv_user')->find($uid);
		//推荐
		//echo $token.'<br>';
		//echo $ad['TOKEN'];die();
		if($token!=$ad['TOKEN']){
			$this->returnjson('2','账户已在其他地方登录！','');
		}
	}
	//json返回
	function returnjson($code,$msg,$ad){
		$data['code']=$code;
		$data['msg']=$msg;
		$data['data']=$ad;
		echo json_encode($data);
		exit();
	}
	//判断会员是否可以观看
	function  checkvip($uid){
		$time=date('Y-m-d H:i:s',time());
		$shop=M('mv_shop_history')->where("SH_USERID=$uid and SH_END>'$time' and IS_PAY=1")->order('id desc')->find();
		if(empty($shop)){
			$user=M('mv_user')->find($uid);
			$times=date('Y-m-1',time());
			$lasttime=date('Y-m-1',strtotime('next month'));
			$son=M('mv_user')->where("PARENT_CODE='$user[EXTENSION_CODE]' and GMT_CREATE>'$times' and GMT_CREATE<'$lasttime'")->select();
			if(empty($son)){
				$this->returnjson('3','请充值！','');
			}
			
		}
	}
	//视频分类接口
	function  classall(){
		$zhang=M('mv_class')->where("IS_DEL=0 and CL_TYPE!=0 and ID!=1")->order('ID asc')->select();
		if(empty($zhang)){
			$zhang=0;
		}
		$this->returnjson('0','成功！',$zhang);
	}
	//分类内容
	public function classcont(){
		$pagesize=$_GET['pagesize'];
		$pageindex=$_GET['pageindex'];
		$pageindex=$pageindex*$pagesize;
		$type=$_GET['type'];
		$cid=$_GET['cid'];
		if(empty($cid)){
			$this->returnjson('1','参数不能为空！','');
		}
		$classname=M('mv_class')->find($cid);
		$data['classname']=$classname['CL_NAME'];
		if($type==1){
	    	$ad=M('mv_movies')->field('ID,MV_NAME as name,MV_PHOTO_URL as pic,MV_TYPE as type,MV_CONTENT as content')->where(" IS_DEL=0 and MV_STATUS=1 and MV_PHYLETIC like '%$cid%'")->order('ID desc')->limit($pageindex,$pagesize)->select();
			if(empty($ad)){
				$ad=0;
			}else{
				foreach($ad as $key=>$value){
				  $ad[$key]['pic']=$this->signurl($value['pic']);
				}
			}
		}else{
			$ad = M("mv_cartoon")->field('ID,CT_NAME as name,CT_PHOTO_URL as pic, CT_TYPE as type,CT_CONTENT as content')
						->where("IS_DEL=0 and CT_STATUS=1 and CT_PHYLETIC=$cid")
						->limit($pageindex,$pagesize)
						->order('ID desc')
						->select();
			//echo M("mv_look_history")->getlastsql();
			if(empty($ad)){
				$ad=0;
			}
	
			if(!empty($ad)){
				foreach($ad as $key=>$val){
					$pic=json_decode($val['pic']);
					$ad[$key]['pic']=$this->signurl($pic['0']);
				}
			}
		}
		$data['data']=$ad;
		$data['code']=0;
		$data['msg']='成功!';
		echo json_encode($data);exit();
		//$this->returnjson('0','成功！',$data);
	}
function make_json_join_str($arr) {
    $ret = '';
    $float_key_arr = array('orderAmountRmb', 'receiveAmountGac', 'userpayAmountRmb');
    foreach($arr as $key => $value) {
        if (is_string($value) && empty($value)) {
            continue;
        }
        if (is_null($value)) {
            continue;
        }
        if (in_array($key, $float_key_arr)) {
            $ret .= ($key . '=' . sprintf("%.2f", $value) . '&');
        } else {
            $ret .= ($key . '=' . $value . '&');
        }
    };
    return substr($ret, 0, -1);
}


//加密算法
//$str加密字符串
//$key私钥
function sign($str, $private_key) {
	$private_key = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($private_key, 64, "\n", true) .
        "\n-----END RSA PRIVATE KEY-----";

    $key = openssl_get_privatekey($private_key);
    //openssl_sign($str, $str_signed, $key, OPENSSL_ALGO_SHA1);
    openssl_sign($str, $str_signed, $key);
    openssl_free_key($key);
    return base64_encode($str_signed);
}


function do_check($content, $sign, $pub_key) {
    $sign = base64_decode($sign);
    $pub_key = "-----BEGIN PUBLIC KEY-----\n" .
        wordwrap($pub_key, 64, "\n", true) .
        "\n-----END PUBLIC KEY-----";
    $key = openssl_pkey_get_public($pub_key);
    return openssl_verify($content, $sign, $key, OPENSSL_ALGO_SHA1);
}


function request_post($url = '', $post_data = array()) {
    if (empty($url) || empty($post_data)) {
        return false;
    }

    $headers = array(
        "Content-type: application/json;charset=utf-8",
        "Accept: application/json",
        "Cache-Control: no-cache",
        "Pragma: no-cache",
        "Access-Control-Allow-Origin:*",
    );

    $post_string = json_encode($post_data);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    // 在尝试连接时等待的秒数
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 6);
    // 最大执行时间
    curl_setopt($ch, CURLOPT_TIMEOUT, 6);
    // String data =  curl_exec($ch);//运行curl
    //qapple返回的json数组
    $response = curl_exec($ch);
    //var_dump($response);
    return $response;
}

    /**
     * 给定一个ip 一个网段 判断该ip是否属于该网段
     * @param $ip
     * @param $networkRange
     * @return bool 属于返回true 不属于返回false
     */
    function judge($ip, $networkRange) {
        if (!isset($ip) || !isset($networkRange)) {
            return false;
        }
        $ip_temp = (double)(sprintf("%u", ip2long($ip)));
        $s = explode('/', $networkRange);
        $network_start = (double)(sprintf("%u", ip2long($s[0])));
        $network_len = pow(2, 32 - $s[1]);
        $network_end = $network_start + $network_len - 1;
        if ($ip_temp >= $network_start && $ip_temp <= $network_end) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 返回 token
     * @param $uid
     * @return string
     */
    function getToken($uid) {
        return md5($uid . time());
    }
}
