<?php
namespace Think;
class Weixin
{
    public $token = NULL;                   //TOKEN
    public $appid = NULL;
    public $appsecret = NULL;
    public $fromUsername = NULL;			//消息来源用户
    public $toUsername = NULL ;				//接受消息的用户
    public $event = FALSE;					//事件
    public $content = NULL;					//信息内容
    public $access_token = NULL ;			//ACCESS_TOKEN内容
    public $get_data = NULL;				//获取数据内容
    public $eventKey = NULL;
    protected $base_site_url = NULL;
    public $msgType;
    const TEXT = 'text';
    const PICARTICLE = 'picarticle';
    protected $wechat_info = array();
    protected $config = array();
    public function __construct(){
		//$this->token = "nw3qGHWWXo90F8BOd0i";
		//$this->verify();
        require_once './Public/wxpay/lib/WxPay.Config.php'; 
		$appid = \WxPayConfig::APPID;
		$secret = \WxPayConfig::APPSECRET;
        
        $this->appid = $appid;
        $this->base_site_url = "http://".$_SERVER['HTTP_HOST'];
        $this->appsecret = $secret;
		
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        libxml_disable_entity_loader(true);
        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $this->fromUsername = $postObj->FromUserName;
        $this->toUsername = $postObj->ToUserName;
        $this->content = trim($postObj->Content);
        $this->event = strtolower($postObj->Event);
        $this->eventKey = $postObj->EventKey;
        $this->msgType = $postObj->MsgType;
        $this->ticket=$postObj->Ticket;
    }
	
	//验证TOKEN
    public function verify(){
        if(!isset($_GET['echostr'])){
			ob_clean();
			echo "";
			return false;
		}
		
        $signature = isset($_GET["signature"]) ? $_GET["signature"]: '';
        $timestamp = isset($_GET["timestamp"]) ? $_GET["timestamp"]: '';
        $nonce = isset($_GET["nonce"]) ? $_GET["nonce"] : '';
        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
			ob_clean();
            echo $_GET["echostr"];
        }else{
			ob_clean();
            echo '';
			exit;
        }
        die;
    }
	
	public function getAppidAndAppsecret(){
            return array(
                'appid'=>$this->appid,
                'appsecret'=>$this->appsecret
            );
    } 

    public function logic(){
		define('CFG_NL', "\n");
		define('CFG_REQUEST_POST',1);
		define('CFG_REQUEST_HTTP','https://');
		define('CFG_REQUEST_HOST','api.accordpost.ru/ff/v1');
		define('CFG_REQUEST_PORT',8080);
		define('CFG_REQUEST_URL','/wsrv');
		define('CFG_REQUEST_FULLURL',"https://api.accordpost.ru/ff/v1/wsrv");
		define('CFG_REQUEST_TIMEOUT', 5);
		define('CFG_CONTENT_TYPE','text/xml');
		define('CFG_TEST_XMLBODY','<request request_type="53" partner_id="300" password="300"><parcel parcel_id="1" zip="630061" weight="1.30"/></request>');



		$o_Curl = curl_init();
		//echo CFG_REQUEST_FULLURL;
		curl_setopt($o_Curl, CURLOPT_URL, CFG_REQUEST_FULLURL);
		curl_setopt($o_Curl, CURLOPT_POST, CFG_REQUEST_POST);
		curl_setopt($o_Curl, CURLOPT_CONNECTTIMEOUT, CFG_REQUEST_TIMEOUT);
		curl_setopt($o_Curl, CURLOPT_HTTPHEADER, array(
		'Content-Type: ' . CFG_CONTENT_TYPE
		, 'Content-Length: ' . strlen(CFG_TEST_XMLBODY)
		, 'Connection: close'
		));
		curl_setopt($o_Curl, CURLOPT_POSTFIELDS,CFG_TEST_XMLBODY);

		curl_setopt($o_Curl, CURLOPT_HEADER,0);
		curl_setopt($o_Curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($o_Curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($o_Curl, CURLOPT_SSL_VERIFYPEER, 0);
		$s_Response=curl_exec($o_Curl);

		echo "data:".$s_Response . CFG_NL;

		curl_close($o_Curl);
		
		exit;
		
		$url = "https://api.accordpost.ru/ff/v1/wsrv/";
		$data ="<request partner_id='300' password='999' request_type='151'/>";
		
		/*$ch = curl_init();
		// set URL and other appropriate options
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		// grab URL, and print
		$a = curl_exec($ch);
		
		curl_close($ch);*/
		$ch = curl_init();  
		$header[] = "Content-type: text/xml";//定义content-type为xml  
		curl_setopt($ch, CURLOPT_URL, $url); //定义表单提交地址  
		curl_setopt($ch, CURLOPT_POST, true);   //定义提交类型 1：POST ；0：GET  
		curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示  
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);//定义是否直接输出返回流  
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data); //定义提交的数据，这里是XML文件 
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		$result = curl_exec($ch);  
		curl_close($ch);//关闭  
		//$ticket = $this->api($url,$post_string);
		dump($result);
        //$this->event();
    }
    
    public function getQrcode($id){

        $name='./Public/userqrcode/qrcode/'.$id.".jpg";
        if(is_file($name)) return $name;

        if(!$this->getAccessToken())  return  "get Qrcode fail!";

        $url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
        $post_string =array(
            'action_name'=>'QR_LIMIT_SCENE',
            'action_info'=>array(
                'scene'=>array(
                    'scene_id'=>$id
                )
            )
        );
        $ticket = $this->api($url,$post_string);
       

        if(!isset($ticket['ticket'])) return 'get Qrcode ticket error!';
        
        $ticket = urlencode($ticket['ticket']);
        $qrcode_url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
        $file = $this->api($qrcode_url,'','GET',true);
        file_put_contents($name,$file);
        return is_file($name)? $name : false;
    }

    
    public function getUserInfo($id){
        if(!$this->getAccessToken())  return  "get userinfo fail!";
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$this->access_token."&openid=".$id."&lang=zh_CN";
        $data = $this->api($url,'','GET');
        $data_userinfo['openid'] = $data['openid'];
        $data_userinfo['nickname'] = $data['nickname'];
        $data_userinfo['headimg'] = $data['headimgurl'];
        return $data_userinfo;
    }



    
    public function event(){
        if($this->event == 'subscribe' || $this->event=='viewscan') {
            $this->isSubscribe(); 
        }else{
			$user = M("user")->where("openid = '".$this->fromUsername."'")->find();
			$key = $this->eventKey;
			if(!empty($key)){
				if($user['status']!=1){
					$s = "抱歉，您还有开通全球众联！\n点击进入  <a href='http://".$_SERVER['HTTP_HOST']."'>立即开通</a>";
					$this->responseMsg($s);
					exit;
				}
			}
			switch($key){
				case 1:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/1'>一键贷款</a>";
					$this->responseMsg($str);
					break;
				case 2:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/2'>银行贷款</a>";
					$this->responseMsg($str);
					break;
				case 3:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/3'>黑户贷款</a>";
					$this->responseMsg($str);
					break;
				case 4:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/4'>学生贷款</a>";
					$this->responseMsg($str);
					break;
				case 5:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/5'>一键办卡</a>";
					$this->responseMsg($str);
					break;
				case 6:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/lists/id/2'>一键提额</a>";
					$this->responseMsg($str);
					break;
				case 7:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/index/id/7'>贷款技术</a>";
					$this->responseMsg($str);
					break;
				case 8:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/index/id/8'>提额养卡技术</a>";
					$this->responseMsg($str);
					break;
				case 9:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/index/id/9'>银行绝密咨询</a>";
					$this->responseMsg($str);
					break;
				case 10:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/yjbh'>一键拨号</a>";
					$this->responseMsg($str);
					break;
				case 11:
					$str = "点击进入  <a href='http://".$_SERVER['HTTP_HOST']."/index.php/daikuan/bianmin'>便民服务</a>";
					$this->responseMsg($str);
					break;
				default:
					echo "";
					break;
			}
            
        }
    }

   protected function curl_post($url, $data = null)
	{
		//创建一个新cURL资源
		$curl = curl_init();
		//设置URL和相应的选项 
		curl_setopt($curl, CURLOPT_URL, $url);
		if (!empty($data)){
			curl_setopt($curl, CURLOPT_POST, 1);
			curl_setopt($chrl, CURLOPT_SAFE_UPLOAD, FALSE);
			@curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//执行curl，抓取URL并把它传递给浏览器
		$output = curl_exec($curl);
		//关闭cURL资源，并且释放系统资源
		curl_close($curl);
		return $output;
	}


    //调取API方法
    protected function api($name, $data = '', $method = 'POST',$returncontent = false){
        $url =$name;
        if(!empty($data)){
            array_walk_recursive($data, function(&$value){
                $value = urlencode($value);
            });
            //$data = urldecode(json_encode($data));
            //$data = urldecode($data);
        }
        $data = self::http($url, $data, $method);
        if($returncontent) return $data;
        return json_decode($data, true);
    }

    //请求远程服务
    protected static function http($url,  $data = '', $method = 'GET'){
        $opts = array(
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        $opts[CURLOPT_URL] = $url;
        if(strtoupper($method) == 'POST'){
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = $data;
            if(is_string($data)){
                $opts[CURLOPT_HTTPHEADER] = array(
                    'Content-Type: application/json; charset=utf-8',
                    'Content-Length: ' . strlen($data),
                );
            }
        }
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data  = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);
        if($error) return "request is error!";
        return  $data;
    }


    //关注
    public function isSubscribe(){
        //解析附加参数
            $key = $this->eventKey;
            $info = M('user')->where('openid = "'.$this->fromUsername.'"')->find();
            if($info){
				$this->responseMsg("你好，众联商务商城欢迎您再次回来！");
				exit;
			}
            if(!empty($key)){
				$data = $this->getUserInfo($this->fromUsername);
				$pid = str_replace("qrscene_",'',$key);
				if(empty($pid)){
					$user['openid']=$data['openid'];
					$user['headimg']=$data['headimg'];
					$user['nickname']=$data['nickname'];
					$user['pid1'] = 0;
					$user['pid2'] = 0;
					$user['pid3'] = 0;
					$uid=M('user')->add($user);
					if(empty($uid)){
						$uid=M('user')->add($user);
					}
					$this->responseMsg("您好，欢迎关注全球众联！您是第".$uid."位会员！");
					exit;
				}
				$puser = M("user")->field("pid1,pid2,pid3,openid")->where("id=".$pid)->find();
                $user['openid']=$data['openid'];
				$user['headimg']=$data['headimg'];
				$user['nickname']=$data['nickname'];
				$user['pid1'] = $pid;
				$user['pid2'] = $puser['pid1'];
				$user['pid3'] = $puser['pid2'];
				$uid=M('user')->add($user);
				if(empty($uid)){
					$uid=M('user')->add($user);
				}
				$this->doSend($puser['openid'],'WLx2nfiV08mfZwcld6Aqp7rZ_B3r_S4SsxJ-uQ1Fk5g','javascript:;',array(
						'first'=>array('value'=>'您好，会员'.$user['nickname'].'通过扫描您的二维码关注了平台！','color'=>"#FF1111"),
						'keyword1'=>array('value'=>$user['nickname'],'color'=>'#FF1111'),
						'keyword2'=>array('value'=>date('Y-m-d H:i:s'),'color'=>'#FF1111'),
						'remark'=>array('value'=>"待开通后才能返现！",'color'=>'#FF1111')
				));
				$this->responseMsg("您好，欢迎关注全球众联！您是第".$uid."位会员！");
				
            }
            
    }

	
	public function createMenu(){
        $this->getAccessToken();

        $url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->access_token;

        $this->api($url , '' , 'GET');

        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        $sub_menu  = array();
        $plist = M('wxmenu')->where(array('pid'=>0))->select(); 
        foreach($plist as $v){
            if($v['name'] == '') continue;
            $tmp_menu = array();
            $son = M('wxmenu')->where(array('pid'=>$v['id']))->select();
            $tmp_menu['name'] = $v['name'];
            
			$s = false;
            foreach ($son as $value) {
                if($value['name'] == '') continue;
				if($value['type']=="view"){
					$tmp_menu['sub_button'][] = array(
						'type'=>'view',
						'name'=> $value['name'],
						'url' => $value['url']
					);
				}else{
					$tmp_menu['sub_button'][] = array(
						'type'=>'click',
						'name'=> $value['name'],
						'key' => $value['url']
					);
				}
				$s = true;
            }
			if($s == false){
				$tmp_menu['type'] = 'view';
				$tmp_menu['url'] = $v['url'];
				
			}
            $sub_menu [] = $tmp_menu;
        }
        $data['button'] = $sub_menu;
    
        $result = $this->api($url,$data);
		
        if($result['errcode'] == 0){
            return true;
        }else{
            return $result;
        }
    }
	

    //获取access_token的验证码
    public function getAccessToken(){
        $apiUrl="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
        $result = $this->api($apiUrl,'','GET');
        if($result){
            $this->access_token = $result['access_token']; //token;
            return true;
        }else{
            return false;
        }
    }
	
	public function qingqiu($url, $data = null)
	{
	 //创建一个新cURL资源
	 $curl = curl_init();
	 //设置URL和相应的选项 
	 curl_setopt($curl, CURLOPT_URL, $url);
	 if (!empty($data)){
	  curl_setopt($curl, CURLOPT_POST, 1);
	  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	 }
	 curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	 //执行curl，抓取URL并把它传递给浏览器
	 $output = curl_exec($curl);
	 //关闭cURL资源，并且释放系统资源
	 curl_close($curl);
	 return $output;
	}
	
	public function ceshi2(){
		$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=image";
		//writelog('147',$url);
		$josn = array('media' => "@./Public/userqrcode/3-p.png");
		//writelog('147',$josn);
		$ret = $this->qingqiu($url,$josn);
		$row = json_decode($ret);//对JSON格式的字符串进行编码
		dump($josn);
		dump($ret);
		dump($row);
		//writelog('147',$row->media_id);
		echo '此素材的唯一标识符media_id为：'.$row->media_id;//得到上传素材后，此素材的唯一标识符media_id

	}
	
	
	public function ceshi($uid){
		$this->getAccessToken();
		$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=".$this->access_token."&type=image";
		$user = M("user")->where("openid = '".$this->fromUsername."'")->find();
		$file = './Public/userqrcode/'.$user['id'].'-p.png';
		if(!file_exists($file)){
			header("Content-type: text/html; charset=utf-8");
			$fileName = $this->getQrcode($user['id']);
			$head = './Public/userqrcode/qrcode/'.$user['id'].'-h.jpg';
			import("ORG.Net.Http");
			$p=new \Org\Net\Http();
			$p->curlDownload($user['headimg'], $head); 
			//$img = file_get_contents($user['headimg']); 
			//file_put_contents($head,$img); 
			$image = new \Think\Image(); 
			$image->open($head);
			$image->thumb(131, 130,\Think\Image::IMAGE_THUMB_FIXED)->save($head);
			$image->open($fileName);
			$image->thumb(225, 225,\Think\Image::IMAGE_THUMB_FIXED)->save($fileName);
			$image->open('./Public/home/images/erweima.jpg')->water($head,15.8,15)->save($file);
			$image->open($file)->water($fileName,20,435)->save($file);
			$image->open($file)->text($user['nickname'],'./ThinkPHP/Library/Think/Verify/zhttfs/stliti.ttf',18,'#000000',180,50)->save($file); 
			//unlink($head);
			//unlink($fileName);
		}
		$josn = array('media' => "@./Public/userqrcode/".$user['id']."-p.png");
		
		$ret = $this->qingqiu($url,$josn);
		$row = json_decode($ret);//对JSON格式的字符串进行编码
		
		$this->responseMsg($row->media_id,self::PICARTICLE);
	}

        //回复内容
    public function responseMsg($contentStr,$msgType=self::TEXT){
        $time = time();
		
        switch($msgType){
            case self::TEXT:            //文本回复
            	$keyword=  $this->content;
            	//dump($keyword);exit;
                if($keyword!=NULL){
					$res=D('zidong')->where("keyword like '%$keyword%'")->find();
					if($res==null){
					 $this->duokefu();
					}else{
						$contentStr=htmlspecialchars_decode($res['content'],ENT_QUOTES);
					}
				}

                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";
                $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $time, $msgType, $contentStr);

                break;
            case self::PICARTICLE:          //图文回复
				/*writelog('369',$this->fromUsername);
				writelog('369',$this->toUsername);
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[news]]></MsgType>
                            <ArticleCount>1</ArticleCount>
                            <Articles>
                            <item>
                            <Title><![CDATA[%s]]></Title>
                            <Description><![CDATA[%s]]></Description>
                            <PicUrl><![CDATA[%s]]></PicUrl>
                            <Url><![CDATA[%s]]></Url>
                            </item>
                            </Articles>
                            </xml>";
                writelog('369',$textTpl);
                $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $time,$contentStr['title'], $contentStr['description'],$contentStr['picurl'],$contentStr['url']);
                writelog('369',$resultStr);
				break;*/
				$textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[image]]></MsgType>
							<Image>
							<MediaId><![CDATA[%s]]></MediaId>
							</Image>
							</xml>";
				
                $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $time,$contentStr);
				
				break;
        }
		
        echo $resultStr;
        die;
    }


    function doSend($touser, $template_id, $url, $data,$topcolor = '#7B68EE') {

        if(!$touser) return false;
        $this->getAccessToken();
        $template = array(
            'touser' => $touser,
            'template_id' => $template_id,
            'url' => $url,
            'topcolor' => $topcolor,
            'data' => $data
        );

        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->access_token;
        //file_put_contents("./tpl_msg.txt", $url."\n");
        // $dataRes = request_post($url,$json_template);
        $dataRes = self::http($url, $json_template, "POST");
        //file_put_contents("./tpl_msg.txt", var_export($dataRes,true),FILE_APPEND);
        if (@$dataRes['errcode'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function duokefu(){
        $time = time();
               //文本回复
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[transfer_customer_service]]></MsgType>
                            </xml>";
        $resultStr = sprintf($textTpl, $this->fromUsername, $this->toUsername, $time);
        echo $resultStr;
        die;
    }



}
