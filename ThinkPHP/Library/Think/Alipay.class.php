<?php
// +----------------------------------------------------------------------
// | TOPThink [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://topthink.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
// | ThinkImage.class.php 2013-03-05
// +----------------------------------------------------------------------

namespace Think;

class Alipay{
    public function pay($param,$notify_url,$return_url){
        header("Content-type:text/html;charset=utf-8");
		require_once("./Public/alipay/alipay_submit.class.php");
		$alipay_config = M("api")->where('id=1')->find();
		$config = array('partner'=>$alipay_config['alipay_id'],'key'=>$alipay_config['alipay_aqxym'],'sign_type'  => 'MD5','input_charset'=> 'utf-8','transport' => 'http','notify_url'=>$notify_url, 'call_back_url'=>$return_url, 'seller_email'=>$alipay_config['alipay_name']);
        //支付类型
        $payment_type = "1";
		
        //商户订单号
        $out_trade_no = $param['WIDout_trade_no'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $param['WIDsubject'];
        //必填

        //付款金额
        $total_fee = $param['total_fee'];
        //必填

        //订单描述

        $body = $param['WIDsubject'];
        //商品展示地址
        $show_url = '';
        //需以http://开头的完整路径，例如：http://www.商户网址.com/myorder.html

        //防钓鱼时间戳
        $anti_phishing_key = "";
        //若要使用请调用类文件submit中的query_timestamp函数

        //客户端的IP地址
        $exter_invoke_ip = "";
        //非局域网的外网IP地址，如：221.0.0.1
		

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "create_direct_pay_by_user",
				"partner" => trim($config['partner']),
				"seller_email" => trim($config['seller_email']),
				"payment_type"	=> $payment_type,
				"notify_url"	=> $notify_url,
				"return_url"	=> $return_url,
				"out_trade_no"	=> $out_trade_no,
				"subject"	=> $subject,
				"total_fee"	=> $total_fee,
				"body"	=> $body,
				"show_url"	=> $show_url,
				"anti_phishing_key"	=> $anti_phishing_key,
				"exter_invoke_ip"	=> $exter_invoke_ip,
				"_input_charset"	=> trim(strtolower("utf-8"))
		);

		//建立请求
		$alipaySubmit = new \AlipaySubmit($config);
		$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
		echo $html_text;
    }
	
	//手机端支付
	public function webpay($param,$notify_url,$return_url){

        header("Content-type:text/html;charset=utf-8");
		require_once("./Public/webalipay/lib/alipay_submit.class.php");
		$config = M("api")->where('id=1')->find();
		$alipay_config = array('partner'=>$config['alipay_id'],'key'=>$config['alipay_aqxym'],'sign_type'  => "MD5",'input_charset'=> 'utf-8','transport' => 'http','notify_url'=>$notify_url, 'call_back_url'=>$return_url,'transport'=>'http', 'seller_email'=>$config['alipay_name']);
        
    	$format = "xml";
		//返回格式
		$v = "2.0";
		//请求号
		$req_id = date('Ymdhis');
		$seller_email=$alipay_config['seller_email'];
		//商户订单号
		$out_trade_no = $param['WIDout_trade_no'];
		//商户网站订单系统中唯一订单号，必填
		
		
		//订单名称
		$subject = $param['WIDsubject'];
		//必填
		//付款金额
		$total_fee = $param['total_fee'];
		//必填

		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $return_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee></direct_trade_create_req>';
		//必填

		/************************************************************/

		//构造要请求的参数数组，无需改动
		$para_token = array(
				"service" => "alipay.wap.trade.create.direct",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		

		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestHttp($para_token);

		//URLDECODE返回的信息
		$html_text = urldecode($html_text);

		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);

		//获取request_token
		$request_token = $para_html_text['request_token'];

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		//构造要请求的参数数组，无需改动
		$parameter = array(
				"service" => "alipay.wap.auth.authAndExecute",
				"partner" => trim($alipay_config['partner']),
				"sec_id" => trim($alipay_config['sign_type']),
				"format"	=> $format,
				"v"	=> $v,
				"req_id"	=> $req_id,
				"req_data"	=> $req_data,
				"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
		);
		
		
		
		//建立请求
		$alipaySubmit = new \AlipaySubmit($alipay_config);
		$html_text = $alipaySubmit->buildRequestForm($parameter, 'get', '确认');
		echo $html_text;
	}
}