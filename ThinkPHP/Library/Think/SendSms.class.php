<?php
namespace Think;
class SendSms{	
	/*
	*PINGTAI: 助通短信平台
	*AUTHOR: 郑州七七网络科技
	*URL： www.cn77.cn
	*DATE: 20150919
	*/
	
	/*
	*账号密码：name:ZTSMS_NAME,PASS:ZTSMS_PASS,MD5后密码,
	*产品ID说明：333334-优质订单通知(hf)，555555-调试专用，676767-优质验证码，181818-优质订单专用，建议使用676767，
	*/
	
	
	//单条发送，定时格式：20130202120212(14位数字)
	public  function send($mobile,$content,$user,$pwd,$pid='676767',$dstime=''){
			$username = "cn77";   //Z5FPPRcF
			$password = "QIAO962870zzcn";  //hnflz
			$productid = $pid;		//内容
			$xh = '';		//留空					
			$url="http://www.ztsms.cn:8800/sendXSms.do?username={$username}&password={$password}&mobile={$mobile}&content={$content}&dstime={$dstime}&productid={$productid}&xh={$xh}"; 			
			$string =self::Get($url);	
            //echo $string;
			$successSign = substr($string,0,2);
			if ($successSign=='1,'){
				$code = substr($string,2,18);
				//成功：1，code: 发送成功时间，mes：提示语；
				$rm=array('status'=>1,'code'=>$code,'mes'=>'发送成功');				
			}else if($successSign=='5,'){	
			    //成功：5，code: 发送成功时间，mes：提示语；
			    $rm=array('status'=>5,'code'=>$code,'mes'=>'定时短信提交成功！');	
			}else if($successSign=='0,'){
				$code = substr($string,2,18);
				//失败：0，code: 发送成功时间，mes：提示语；
			    $rm=array('status'=>5,'code'=>$code,'mes'=>'短信发送失败，原因未知！');	
			}else{
				$code = intval($string);
				switch ($code)
				{
				case -1:
				  $mes='短信用户名或者密码不正确或用户禁用';
				  break; 
				case 2:
				  $mes='短信条数不足！';
				  break;
				case 3:
				  $mes='扣款失败！';
				  break;
				case 6:
				  $mes='手机号码为空！';
				  break;
				case 7:
				  $mes='短信内容为空！';
				  break;
				case 8:
				  $mes='短信没有签名！';
				  break;
				case 9:
				  $mes='改操作权限不足！';
				  break;
				case 10:
				  $mes='每次最多只能提交200个号码！';
				  break;
				case 11:
				  $mes='产品ID异常！';
				  break;
				case 12:
				  $mes='参数异常！';
				  break;
				case 13:
				  $mes='30分钟内重复提交！';
				  break;
				case 15:
				  $mes='IP地址验证失败！';
				  break;
				case 19:
				  $mes='短信内容过长！';
				  break;
				case 20:
				  $mes='定时时间不正确！';
				  break;
				default:
				  $mes='未知原因！';
				}
                //失败：0，code: 错误代码，mes：错误提示；			
				$rm=array('status'=>0,'code'=>$code,'mes'=>$mes);
			}
			return $rm;		
    }
	
	public  function getSendStatus(){
		    //自动获取发送短信的状态，该方法为被动接受短信平台返回的数据信息
			//说明：获取短信内容成功后，需要给平台反馈"0",表示接收成功。
		    if(empty($_GET)){echo '没有任何数据！';}
			$msgid=trim($_GET['msgid']);
			$mobile=trim($_GET['mobile']);
			$status=trim($_GET['status']);
			if($msgid==''){$rm=array('status'=>0,'mes'=>'消息编号错误！');}
			if($mobile==''){$rm=array('status'=>0,'mes'=>'手机号码错误！');}
			if($status==''){$rm=array('status'=>0,'mes'=>'状态内容为空！');}
			
	        $rm = array('status'=>1,'msgid'=>$msgid,'mobile'=>$mobile,'zt'=>$status);
			return $rm;
    }
	
	
	public  function getHuifu(){
		    //获取用户回复的短信内容，该方法为被动接受短信平台返回的数据信息
			//说明：获取短信内容成功后，需要给平台反馈"0",表示接收成功。
		    if(empty($_GET)){echo '没有任何数据！';}
			$msgid=trim($_GET['msgid']);
			$mobile=trim($_GET['mobile']);
			$content=trim($_GET['content']);
			$xh=trim($_GET['xh']);
			if($msgid==''){$rm=array('status'=>0,'mes'=>'消息编号错误！');}
			if($mobile==''){$rm=array('status'=>0,'mes'=>'手机号码错误！');}
			if($content==''){$rm=array('status'=>0,'mes'=>'回复内容为空！');}
			
	        $rm = array('status'=>1,'msgid'=>$msgid,'mobile'=>$mobile,'content'=>$content,'xh'=>$xh);
			return $rm;
    }
	
    public function Get($url){
		if(function_exists('file_get_contents')){
			$file_contents = file_get_contents($url);
		}else{
			$ch = curl_init();
			$timeout = 5;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$file_contents = curl_exec($ch);
			curl_close($ch);
		}
		return $file_contents;
	} 
	
}
?>
