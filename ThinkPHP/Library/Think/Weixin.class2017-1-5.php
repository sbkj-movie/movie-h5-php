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
		$this->token = "nw3qGHWWXo90F8BOd0i";
		$this->verify();
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
	
	public function msg(){
		$this->responseMsg("欢迎您的关注！");
	}
   
    public function logic(){
        $this->event();
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
            echo "";
			exit;
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
            $data = urldecode(json_encode($data));
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
				$this->responseMsg("欢迎您的关注！");
			}
            if(!empty($key)){
				$data = $this->getUserInfo($this->fromUsername);
				$pid = str_replace("qrscene_",'',$key);
				if(empty($pid)){
					$this->responseMsg("欢迎您的关注！");
					exit;
				}
				$puser = M("user")->field("pid1,pid2,pid3")->where("id=".$pid)->find();
                $user['openid']=$data['openid'];
				$user['headimg']=$data['headimg'];
				$user['nickname']=$data['nickname'];
				$user['pid1'] = $pid;
				$user['pid2'] = $puser['pid2'];
				$user['pid3'] = $puser['pid3'];
				$uid=M('user')->add($user);
				if($uid){
					$head = './Public/userqrcode/qrcode/'.$uid.'-h.jpg';
					import("ORG.Net.Http");
					$p=new \Org\Net\Http();
					$p->curlDownload($user['headimg'], $head);
					$ewm = $this->getQrcode($uid);
					$image = new \Think\Images(); 
					$image->open($head);
					$image->thumb(81, 81,\Think\Image::IMAGE_THUMB_FIXED)->save($head);
					$image->open('./Public/home/images/ewm.jpg')->water($head,169,810)->save($file);
					$image->open($ewm);
					$image->thumb(250, 250,\Think\Image::IMAGE_THUMB_FIXED)->save($ewm);
					$image->open($file)->water($ewm,200,520)->save($file);
					$image->open($file)->text($user['nickname'],'./ThinkPHP/Library/Think/Verify/zhttfs/stliti.ttf',16,'#f8b62b',337,828)->save($file); 
					unlink($head);
				}else{
					$uid=M('user')->add($user);
					if($uid){
						$head = './Public/userqrcode/qrcode/'.$uid.'-h.jpg';
						import("ORG.Net.Http");
						$p=new \Org\Net\Http();
						$p->curlDownload($user['headimg'], $head);
						$ewm = $this->getQrcode($uid);
						$image = new \Think\Images(); 
						$image->open($head);
						$image->thumb(81, 81,\Think\Image::IMAGE_THUMB_FIXED)->save($head);
						$image->open('./Public/home/images/ewm.jpg')->water($head,169,810)->save($file);
						$image->open($ewm);
						$image->thumb(250, 250,\Think\Image::IMAGE_THUMB_FIXED)->save($ewm);
						$image->open($file)->water($ewm,200,520)->save($file);
						$image->open($file)->text($user['nickname'],'./ThinkPHP/Library/Think/Verify/zhttfs/stliti.ttf',16,'#f8b62b',337,828)->save($file); 
						unlink($head);
					}
				}
				$this->responseMsg("欢迎您的关注！！");
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
						'key' => $value['key']
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
	
	public function createMenu2(){
        $this->getAccessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token;
        //先删除已有的菜单
        $delurl="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->access_token;
        $result = $this->api($delurl,'','GET');     //先删除菜单

        $data = array(
            'button'=>array(
                array(
                    'name'=>'商城',
                    'sub_button'=>array(
                          array(
                            "type"=>"view",
                            "name"=>"商城首页",
                             "url"=>$this->base_site_url
                          ),
                          array(
                            "type"=>"view",
                            "name"=>"我的订单",
                             "url"=>$this->base_site_url.'/index.php/order/lists'
                          ),

                          array(
                            "type"=>"view",
                            "name"=>"个人中心",
                             "url"=>$this->base_site_url.'/index.php/user/index'
                          ),

                          array(
                            "type"=>"view",
                            "name"=>"积分兑换",
                            "url"=>$this->base_site_url.'/index.php/credit/lists'
                          ),
                        
                          array(
                            "type"=>"view",
                            "name"=>"兑换记录",
                            "url"=>$this->base_site_url.'/index.php/credit/index'
                          ),
                        )
                    
                    ),
                array(
                    'name'=>'分销中心',
                    'sub_button'=>array(
                        array(
                            "type"=>"view",
                            "name"=>"申请分销商",
                            "url"=>$this->base_site_url.'/index.php/user/fenxiao'
                          ),
                          array(
                            "type"=>"view",
                            "name"=>"推广二维码",
                            "url"=>$this->base_site_url.'/index.php/user/erweima'
                          )
                        )
                    ),
                array(
                    'name'=>'签到',
					"type"=>"view",
                    "url"=>$this->base_site_url.'/index.php/user/sign'
                )
            )
        );

        $result = $this->api($url,$data);
        //file_put_contents('./menu.txt', var_export($result,true),FILE_APPEND);
        if($result['errcode'] == 0){
            return true;
        }else{
            return false;
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
            'url' => '',
            'topcolor' => $topcolor,
            'data' => $data
        );

        $json_template = json_encode($template);
        $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=" . $this->access_token;
        //file_put_contents("./tpl_msg.txt", $url."\n");
        // $dataRes = request_post($url,$json_template);
        $dataRes = self::http($url, $json_template, "POST");
        file_put_contents("./tpl_msg.txt", var_export($dataRes,true),FILE_APPEND);
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
